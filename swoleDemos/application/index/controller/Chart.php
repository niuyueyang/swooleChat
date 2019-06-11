<?php
namespace app\index\controller;
require __DIR__."/../../common/lib/RedisMy.php";
class Chart
{
    public $redis;
    public function __construct()
    {
        $this->redis=new \RedisMy();
    }
    public function index()
    {
       if(empty($_POST['swooleData']['game_id'])){
           return json_encode(array('code'=>1,'data'=>1,'msg'=>'game_id为空','data'=>array()));
       }
        if(empty($_POST['swooleData']['content'])){
            return json_encode(array('code'=>1,'data'=>1,'msg'=>'content为空','data'=>array()));
        }
        $data=array(
            'user'=>'user'.rand(0,2000),
            'content'=>$_POST['swooleData']['content'],
            'mType'=>'chat',
            'ipconfig'=>9601
        );
        //redis发布订阅
        $redisR = new \Redis();
        $redisR->connect('127.0.0.1',6379);
        $msg=$redisR->publish('chat',json_encode($data));
        echo $msg;

        $clients=$this->redis->sMembers('personId_9601');
        foreach($clients as $fd)
        {
            $_POST['http_server']->push($fd, json_encode($data));
        }
        return json_encode(array('code'=>0,'msg'=>'success'));
    }
    public function indexs()
    {
        if(empty($_POST['swooleData']['game_id'])){
            return json_encode(array('code'=>1,'data'=>1,'msg'=>'game_id为空','data'=>array()));
        }
        if(empty($_POST['swooleData']['content'])){
            return json_encode(array('code'=>1,'data'=>1,'msg'=>'content为空','data'=>array()));
        }
        $data=array(
            'user'=>'user'.rand(0,2000),
            'content'=>$_POST['swooleData']['content'],
            'mType'=>'chat'
        );
        $clients=$this->redis->sMembers('personIds');
        foreach ($_POST['http_server']->connection_list() as $fd){
            $_POST['http_server']->push($fd,json_encode($data));
        }
    }
}
