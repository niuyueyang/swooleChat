<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/18
 * Time: 15:19
 */
$server = new swoole_websocket_server("0.0.0.0", 9513);

//websocket连接
$server->on('open','onOpen');

function onOpen($server,$request){
    print_r($request->fd);
}

//监听websocket消息事件
$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();