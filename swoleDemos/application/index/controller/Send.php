<?php
namespace app\index\controller;

use think\Exception;
require __DIR__."/../../common/lib/RedisMy.php";

class Send
{
    public $redis;
    public function __construct()
    {
        $this->redis=new \RedisMy();
    }

    public function index(){
        $phoneNum=request()->post('phone_num',0,'intval');
        if(empty($phoneNum)){
            return json_encode(array('data'=>1,'msg'=>'phone number empty','data'=>array()));
        }
        else{
            $funAndOperate = "industrySMS/sendSMS";
            // 参数详述请参考http://miaodiyun.com/https-xinxichaxun.html
            // 生成body
            $ACCOUNT_SID = "d4e3b7a7bf1b4bf08a6dc5acaa1c10b0"; // 主账户
            $AUTH_TOKEN = "eb0e9aaaf37a42d5a17a10b7405aff89";
            $timestamp = date("YmdHis");
            // 签名
            $sig = md5($ACCOUNT_SID . $AUTH_TOKEN . $timestamp);

            $body = array("accountSid" => $ACCOUNT_SID, "timestamp" => $timestamp, "sig" => $sig, "respDataType"=> "JSON");
            $code=mt_rand(999, 9999);
            //return json_encode(array('code'=>0,'msg'=>'短信发送成功','data'=>$this->redis->get($this->redis->smsKey($phoneNum))));
            // 在基本认证参数的基础上添加短信内容和发送目标号码的参数
            $body['smsContent'] = "【怀仁县天天香】您的验证码为".$code."，请于5分钟内正确输入，如非本人操作，请忽略此短信。";
            $body['to'] = $phoneNum;
            // 提交请求
            $taskData=[
                'method'=>'sendSms',
                'data'=>[
                    'phone'=>$phoneNum,
                    'code'=>$code,
                    'funAndOperate'=>$funAndOperate,
                    'body'=>$body,
                    'redis'=>$this->redis
                ]
            ];
            $_POST['http_server']->task($taskData);
            //$result = json_decode($this->postData($funAndOperate, $body));
            $this->redis->set($this->redis->smsKey($phoneNum), $code, config('redis.out_time'));
            $smsKey=$this->redis->get($this->redis->smsKey($phoneNum));
            if($smsKey){
                return json_encode(array('code'=>0,'msg'=>'发送成功','data'=>array(),'smsCode'=>$this->redis->get($this->redis->smsKey($phoneNum))));
            }
            else{
                return json_encode(array('code'=>1,'msg'=>'发送失败','data'=>array()));
            }
        }
    }

    public function postData($funAndOperate, $body){
        // 构造请求数据
        $url = "https://api.miaodiyun.com/20150822/".$funAndOperate;
        $CONTENT_TYPE = "application/x-www-form-urlencoded";
        $ACCEPT = "application/json";
        $headers = array('Content-type: ' . $CONTENT_TYPE, 'Accept: ' . $ACCEPT);
//        echo("url:<br/>" . $url . "\n");
//        echo("<br/><br/>body:<br/>" . json_encode($body));
//        echo("<br/><br/>headers:<br/>");
        // 要求post请求的消息体为&拼接的字符串，所以做下面转换
        $fields_string = "";
        foreach ($body as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        // 提交请求
        $con = curl_init();
        curl_setopt($con, CURLOPT_URL, $url);
        curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($con, CURLOPT_HEADER, 0);
        curl_setopt($con, CURLOPT_POST, 1);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
        $result = curl_exec($con);
        curl_close($con);

        return "" . $result;
    }


    public function curl_get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $dom = curl_exec($ch);
        curl_close($ch);
        return $dom;
    }
    //curl post
    public function curl_post($url, $postdata)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        return $result;
    }

    /*验证码*/

    /*登录*/
    public function login(){
        $phoneNum=request()->post('phone_num',0);
        $code=request()->post('phone_code',0);
        if(empty($_POST['swooleData']['phone_code'])||empty($_POST['swooleData']['phone_num'])){
            return json_encode(array('data'=>1,'msg'=>'phone or code empty','data'=>array('phoneNum'=>$_POST['swooleData']['phone_num'],'code'=>$_POST['swooleData']['phone_code'])));
        }
        else{
            $redisCode=$this->redis->get('sms_'.$_POST['swooleData']['phone_num']);
            if($_POST['swooleData']['phone_code']==$redisCode){
                return json_encode(array('code'=>0,'msg'=>'login success','data'=>array()));
            }
            else{
                return json_encode(array('code'=>1,'msg'=>'login fail','data'=>array('redisCode'=>$redisCode,'msg'=>'redis code no exist')));
            }
        }
    }
}
