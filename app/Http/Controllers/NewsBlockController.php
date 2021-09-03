<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsBlock;
use Illuminate\Support\Facades\Validator;

class NewsBlockController extends Controller
{
    public function show(Request $request, $id=null)
    {

        if($request->ajax()){

            $input = $request->all();

            if(isset($input['id'])){
                $block = NewsBlock::find($input['id']);
                return view('newsblock.edit',compact('block'));
            }

        }

    }

    public function update(Request $request, $id=null)
    {
        if($request->ajax()){

            $input = $request->all();

            $validator = Validator::make(
                [
                    'data' => $input
                ],
                [
                    'data.id' => 'required|integer',
                    'data.name' => 'required|string',
                    'data.shortlink' => 'required|url',
                    'data.description' => 'required|string',
                    'data.author' => 'string|min:0',
                    'data.publish_date' => 'required|date_format:Y-m-d H:i:s',
                    'data.images' => 'required|json',

                ]
            );

            if ($validator->fails()) {
                return "Ошибка валидации данных".$validator->errors();
            }

            $block = NewsBlock::find($input['id']);
            $block->name=$request['name'];
            $block->shortlink=$request['shortlink'];
            $block->description=$request['description'];
            $block->author=$request['author']??'';
            $block->publish_date=$request['publish_date'];
            $block->images=$request['images']??'[]';

            $block->save();

            return "Данные обновлены";

        }

        return "Ошибка обновления данных";

    }

    public function delete(Request $request, $id=null)
    {

        if($request->ajax()){

            $input = $request->all();

            $validator = Validator::make(
                [
                    'data' => $input
                ],
                [
                    'data.id' => 'required|integer'
                         ]
            );

            if ($validator->fails()) {
                return "Ошибка валидации данных";
            }

            $block = NewsBlock::find($input['id']);
            $block->delete();
            return "Новостной блок удален";

        }
    }

}
