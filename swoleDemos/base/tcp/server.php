<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/18
 * Time: 9:36
 */
//创建Server对象，监听 127.0.0.1:9512端口
$serv = new swoole_server("127.0.0.1", 9512);

$serv->set([
    'worker_num' => 4, //进程数
    'max_request'=>10000,
    'daemonize' => true,
    'backlog' => 128,
]);
//监听连接进入事件
/*
 * $fd 客户端唯一标识
 * $reactor_id线程id
 *
 * */
$serv->on('connect', function ($serv, $fd, $reactor_id) {
    echo "Client: {$reactor_id} -- {$fd}Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $reactor_id, $data) {
    $serv->send($fd, "Server: {$reactor_id} -- {$fd}".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
    echo "Client: Close.\n";
});

//启动服务器
$serv->start();