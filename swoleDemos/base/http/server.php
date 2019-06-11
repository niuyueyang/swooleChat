<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/18
 * Time: 13:44
 */
$http=new swoole_http_server('0.0.0.0',8811);
$http->on('request', function ($request, $response) {
    $response->end("<h1>Hello Swoole. #".rand(1000, 9999)."</h1>");
});
$http->start();
