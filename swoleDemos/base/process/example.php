<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/1
 * Time: 15:37
 */
date_default_timezone_set("PRC");
echo 'process-start：'.date('Ymd H:i:s').PHP_EOL;
$wokers=[];
$urls=[
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com'
];
for($i=0;$i<count($urls);$i++){
    //子进程
    $process=new swoole_process(function (swoole_process $worker) use($i,$urls){
        //这里必须把外面的$urls放到use里面，否则请求不到
        $content=curlData($urls[$i]);
        //echo $content.PHP_EOL;
        //写入管道
        $worker->write($content);
    });
    $pid=$process->start();
    $workers[$pid]=$process;
}

//由于设置为true，打印不出来，所以必须循环
foreach ($workers as $process){
    echo $process->read();
}
//模拟请求
function curlData($url){
    sleep(1);
    return $url.' success '.PHP_EOL;
}
echo 'process-end：'.date('Ymd H:i:s').PHP_EOL;