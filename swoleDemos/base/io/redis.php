<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/1
 * Time: 9:14
 */
$client=new swoole_redis;
$client->connect('127.0.0.1',6379,function (swoole_redis $client,$result){
    echo 'connect'.PHP_EOL;
    echo $result.PHP_EOL;
    $client->set('swooleRedis',time(),function (swoole_redis $client,$result){
        var_dump($result).PHP_EOL;
    });
    $client->get('swooleRedis',function (swoole_redis $client,$result){
        var_dump($result);
        $client->close();
    });
    $client->keys('*',function (swoole_redis $client,$result){
        var_dump($result);
        $client->close();
    });
});