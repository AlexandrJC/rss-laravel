<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\NewsBlock;
use App\Models\Interfaces\IRssToNewsBlockConvertable;
use App\Models\Interfaces\IRssDataProvider;

use DateTime;
use Illuminate\Support\Facades\DB;


class RssConverterController extends Controller implements IRssToNewsBlockConvertable
{

    private NewsBlock $NewsBlock;

    private array $RssRequestData;

    public function __construct(IRssDataProvider $rss_req_data_provider = null)
    {

        $this->RssRequestData = $rss_req_data_provider->getParserData();

        $validator= Validator::make(
            [
                'data' => $this->RssRequestData
            ],
            [
                'data.url' => 'url|max:1000',
                'data.news_tag_name' => 'string|min:1|max:20',
                'data.converter_array' => 'required|array',
                'data.converter_array.name'=>'required',
                'data.converter_array.shortlink' => 'required',
                'data.converter_array.description' => 'required',
                'data.converter_array.publish_date' => 'required',
                'data.converter_array.author' => 'required',
                'data.converter_array.images' => 'required'
            ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Используются некорректные данные для парсера');
        }

        $this->RssRequestDataProvider=$rss_req_data_provider;

        $this->NewsBlock=new NewsBlock();
    }

    public function getRequestData(): array
    {
        return $this->RssRequestData;
    }

    public function makeConvert(array $kvarray): NewsBlock
    {
        if(isset($kvarray['publish_date'])){
            $dt=new DateTime($kvarray['publish_date']);
            $kvarray['publish_date']=$dt->format('Y-m-d H:i:s');
        }

        if(isset($kvarray['images'])){
            $kvarray['images']=json_encode($kvarray['images']);
        }


        DB::enableQueryLog();

        try {
            $results = NewsBlock::create($kvarray)->toSql();
        } catch (\Throwable $e) {
            // dd(
            //   $e
            // );
        }

        return $this->NewsBlock;

    }

}
