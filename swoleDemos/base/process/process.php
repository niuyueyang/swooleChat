<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/1
 * Time: 15:09
 */
$process=new swoole_process(function (swoole_process $pro){
    echo 11;
    //如果设置为true，这里11不会输出，设置为false，则会输出11；
    //$pro->exec第一个参数为linux上安装php目录，第二个参数是要执行的文件，类似于服务器上执行的php ws.php
    $pro->exec('/usr/bin/php',[__DIR__ . '/../ws.php']);
    //利用 ps aux | grep /php/swoleDemo/process/process.php 检测
    //pstree -p 23660 【23660为下面echo输出的】
},true);
$pid=$process->start();
echo $pid.PHP_EOL;  //子进程id
swoole_process::wait();
