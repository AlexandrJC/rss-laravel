<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebRequestLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redis;

class WorkerController extends Controller
{

    private int $Pid;

    private int $LastUpdate;

    private string $LogFile='/tmp/worker';

    private string $AtrisanPath='/var/www/laravel';

    public function __construct()
    {
        $this->Pid=Redis::get('WorkerPid')??0;
        $this->LastUpdate=Redis::get('LastUpdate')??0;

    }

    public function start()
    {
        $data=['message'=>'Невозможно повторно запустить Worker'];

        if($this->Pid == 0 && $this->LastUpdate ==0 && !file_exists($this->LogFile)){
            $command = 'cd '.$this->AtrisanPath.'; nohup php artisan schedule:work > '.$this->LogFile.' 2>&1 & echo $!';
            exec($command ,$op);
            $pid = (int)$op[0];

            if(is_int($pid)){
                Redis::set('WorkerPid', $pid);
                Redis::set('LastUpdate', "1000");
            }

            $data['message']="Worker запущен PID ".$pid;

         }

        return $data;
    }

    public function stop()
    {
        $data=['message'=>'Worker уже остановлен'];

        if(file_exists($this->LogFile) && isset($this->Pid)){
            exec('kill '.$this->Pid);
            unlink($this->LogFile);
            Redis::del('WorkerPid');
            Redis::del('LastUpdate');
            $data['message']='Worker остановлен PID '.$this->Pid;
        }

        return $data;

    }

    public function test()
    {
        return __METHOD__;
    }
}
