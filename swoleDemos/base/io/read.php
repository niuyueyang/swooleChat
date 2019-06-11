<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/27
 * Time: 18:37
 */
//读取
//读取文件最大读取4M
$result=swoole_async_readfile(__DIR__ . "/1.txt",function ($filename, $filecontent){
    echo '文件名：'.$filename.PHP_EOL;
    echo '文件内容：'.$filecontent.PHP_EOL;
});
//分段读取函数【https://wiki.swoole.com/wiki/page/188.html】
$result1=swoole_async_read(__DIR__ . "/2.txt",function ($filename, $filecontent){
    echo '文件名：'.$filename.PHP_EOL;
    echo '文件内容：'.$filecontent.PHP_EOL;
},8192,0);
echo $result1.PHP_EOL; //true

//写文件【最大支持4M】
$result2=swoole_async_writefile(__DIR__ . '/write.txt','我是要写入的内容'.PHP_EOL,function ($filename){
    echo '写入成功'.PHP_EOL;
},FILE_APPEND);//FILE_APPEND追加
