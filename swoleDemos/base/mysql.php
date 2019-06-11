<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/2/28
 * Time: 13:49
 */
class mySql{
    public $dbSource='';
    public $dbconfig=[];
    public function __construct(){
        $this->dbSource=new Swoole\MySQL;
        $this->dbconfig=[
            'host'=>'127.0.0.1',
            'port'=>3306,
            'user'=>'root',
            'password'=>'asdf123456A*',
            'database'=>'swoole',
            'charset'=>'utf8'
        ];
    }
    public function execute($id,$username){
        //因为是在回调函数里面使用，所以如果要使用$username，必须使用闭包use ($id,$username)
        $this->dbSource->connect($this->dbconfig,function ($db,$result) use ($id,$username){
            if($result==false){
                echo 'error';
                var_dump($db->connect_errno, $db->connect_error);
            }
            else{
                $sql='select * from test where id=1';
                $sql1="update test set username = '".$username."' where id = ".$id;

                $db->query($sql1,function ($db,$result){
                    //增删改，$result返回Boolean
                    //查询 $result返回内容
                    if($result===true){
                        var_dump($result);
                    }
                    elseif($result===false){
                        var_dump($result);
                    }
                    else{
                        var_dump($result);
                    }
                    $db->close();
                });
            }
        });
        return true;
    }

}
$obj=new mySql();
$obj->execute(1,'jsp');
