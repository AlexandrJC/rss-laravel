<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class NewsBlock
 * @package App
 * @mixin Builder
 */

class NewsBlock extends Model
{
    protected $guarded = [];

    public function GetFirstImage(): string
    {

        if($this->images !=null && $this->images !=''){

            $images = json_decode($this->images);

            if(isset($images[0])){
                return $images[0];
            }

        }

        return '';

    }

    public function GetPublishDate()
    {
        return Carbon::parse($this->publish_date)->diffForHumans();
    }

    public function GetAuthorText(){
        if($this->author != null && $this->author!=''){
            return 'Автор: '.$this->author;
        }
        return '';
    }

}
