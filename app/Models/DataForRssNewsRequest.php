<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Interfaces\IRssDataProvider;

/**
 * Class DataForRssRequest
 * @package App
  */

class DataForRssNewsRequest extends Model implements IRssDataProvider
{

    public string $Url='http://static.feed.rbc.ru/rbc/logical/footer/news.rss';

    public array $ConverterArray =  array(
        'name' => 'title',
        'shortlink' => 'link',
        'description' => 'description',
        'publish_date' => 'pubDate',
        'author' => 'author',
        'images' => array(
            'tag_name' => 'enclosure',
            'attr_name' => 'url',
            'compare_attr' => array (
                'key' => 'type',
                'value' => 'image/jpeg'
            ),
        )
    );

    public string $NewsTagName ='item';

    public function getParserData(): array{

        return [
            'url'=>$this->Url,
            'news_tag_name'=>$this->NewsTagName,
            'converter_array'=>$this->ConverterArray,
        ];

    }

}
