<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/30
 * Time: 19:34
 */
namespace app\admin\controller;
require __DIR__."/../../common/lib/RedisMy.php";
class Live
{
    public $redis;
    public $clients;
    public function __construct()
    {
        $this->redis=new \RedisMy();
        $this->clients=$this->redis->sMembers('personId_9601');
    }
    public function push(){
        $contentSwoole=$_POST['swooleData'];
//        if(empty($_POST['swooleData'])){
//            return json_encode(array('code'=>1,'msg'=>'信息不能为空','data'=>array()));
//        }

        //这里将球队对应logo展示出来，真实环境需要mysql查询，这里暂且写死
        $teams=array(
            1=>array(
                'name'=>'马刺',
                'logo'=>'/static/live/imgs/team1.png'
            ),
            4=>array(
                'name'=>'火箭',
                'logo'=>'/static/live/imgs/team2.png'
            ),
        );
        $data=array(
            'type'=>intval($_POST['swooleData']['type']),
            'title'=>!empty($teams[$_POST['swooleData']['team_id']]['name'])?$teams[$_POST['swooleData']['team_id']]['name']:'直播员',
            'logo'=>!empty($teams[$_POST['swooleData']['team_id']]['logo'])?'http://39.106.10.163:9599/'.$teams[$_POST['swooleData']['team_id']]['logo']:'http://39.106.10.163:9599/static/live/imgs/team2.png',
            'time'=>date('H:i:s'),
            'content'=>!empty($_POST['swooleData']['content'])?$_POST['swooleData']['content']:'',
            'image'=>!empty($_POST['swooleData']['image'])?$_POST['swooleData']['image']:'',
            'mType'=>'live'
        );
        $clients=$this->redis->sMembers('personId_9601');
//        foreach ($_POST['http_server']->connections as $val){
//            $_POST['http_server']->push($val,json_encode($data));
//        }
        foreach($this->clients as $fd)
        {
            $_POST['http_server']->push($fd, json_encode($data));
        }
//        $_POST['http_server']->push(7,'hello swoole');
//        print_r($_POST);
//        $port = 6379;
//        $redis = new \Redis();
//        $redis->connect('127.0.0.1',$port, 300);

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
            'code'=>9601
        );
//        var_dump($_POST['fd']);
//        $clients=$this->redis->sMembers('personId');
//        var_dump($this->clients);
//        for($i=0;$i<count($this->clients);$i++){
//            var_dump($this->clients[$i]);
//        }
        $redisR = new \Redis();
        $redisR->connect('127.0.0.1',6379);
        $msg=$redisR->publish('chat',json_encode($data));
        echo $msg;
        foreach($this->redis->sMembers('personId_9601') as $fd)
        {
            $clientId=(int)$fd;
            $_POST['http_server']->push($clientId, json_encode($data));
        }
    }
    public function sendOther(){
        $msg=json_encode($_POST['swooleData']);
        print_r($msg);
        $redisR = new \Redis();
        $redisR->connect('127.0.0.1',6379);
        $redisR->set('msg',$msg);
        echo 111;
        foreach($this->redis->sMembers('personId_9601') as $fd)
        {
            $clientId=(int)$fd;
            $_POST['http_server']->push($clientId, $msg);
        }
    }
}