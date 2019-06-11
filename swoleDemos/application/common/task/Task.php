<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/8
 * Time: 17:14
 */
class Task{
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
    /*异步发送验证码*/
    public function sendSms($data){
        try{
            $result = json_decode($this->postData($data['funAndOperate'], $data['body']));
            if($result->respCode=='00000'){
                $data['redis']->set($data['redis']->smsKey($data['phone']), $data['code'], config('redis.out_time'));
            }
            else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
        return true;
    }
    public function test(){
        echo 111;
    }
}