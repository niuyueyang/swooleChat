<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/18
 * Time: 11:25
 */
$client=new swoole_client(SWOOLE_SOCK_TCP);
if(!$client->connect('127.0.0.1',9512)){
    echo '连接失败';
    exit;
}
fwrite(STDOUT,'请输入消息');
$msg=trim(fgets(STDIN));

//发送信息到server
$client->send($msg);

//接收来自服务端信息
$result=$client->recv();
echo $result;



