<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebRequestLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;


class WebRequestLogController extends Controller
{

    public static function saveRequestData(array $log_data)
    {

        $validator= Validator::make(
            [
                'data' => $log_data
            ],
            [
                'data.request_method' => 'required|string|min:1|max:20',
                'data.request_url' => 'required|url|string|min:1|max:1000',
                'data.responce_body' => 'required|string|min:0',
                'data.responce_http_code' => 'required|integer',
            ]);


        if ($validator->fails()) {

            $f = $validator->failed();
            var_dump($f); die();

            throw new \InvalidArgumentException('Невозможно записать данные в лог парсера. Данные не соответствуют требованиям <br>');
        }

        $results = WebRequestLog::create($log_data)->toSql();

    }

    public function index()
    {
        $blocks = WebRequestLog::orderBy('id','desc')->paginate(3);

        return view('user.log',compact('blocks'));
    }


    public function test()
    {
        return __METHOD__;
    }
}
