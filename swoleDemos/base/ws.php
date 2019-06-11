<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/18
 * Time: 16:32
 */
class Ws{
    const HOST='0.0.0.0';
    const PORT=9513;
    public $ws=null;
    public function __construct(){
        $this->ws = new swoole_websocket_server("0.0.0.0", 9513);
        $this->ws->set([
            'worker_num'=>2,
            'task_worker_num'=>2
        ]);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('close',[$this,'onClose']);
        $this->ws->start();
    }
    public function onOpen($ws,$request){
        var_dump($request->fd);
        if($request->fd==1){
            swoole_timer_tick(2000,function ($timer_id){
                echo "2s timer_id {$timer_id}\n";
            });
        }
    }
    public function onMessage($ws,$frame){
        date_default_timezone_set("Asia/Shanghai");
        echo "{$frame->data}\n";
        $data=[
            'task'=>1,
            'fd'=>$frame->fd
        ];
        //$ws->task($data);
        swoole_timer_after(5000,function () use($ws,$frame){
            echo "5s after";
            $ws->push($frame->fd,'server-timer-after');
        });
        $ws->push($frame->fd,date('Y-m-d H:i:s'));
    }
    public function onTask($serv,$taskId,$workerId,$data){
        print_r($data);
        sleep(10);
        return 'task finish';
    }
    public function onFinish($serv,$taskId,$data){
        echo "taskId：{$taskId}\n";
        echo "finish-data-success：{$data}\n";

    }
    public function onClose($ws,$fd){
        echo "{$fd} leave\n";
    }
}
$ws=new Ws();