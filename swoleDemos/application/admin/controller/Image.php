<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/30
 * Time: 19:34
 */
namespace app\admin\controller;
class Image
{
    public function Index(){
        $name=date("YmdHis").$_FILES["file"]["name"];
        $filename = "/php/tp/public/static/file/".$name;
        if (file_exists($filename))
        {
            return json_encode(array('code'=>1,'msg'=>'文件已存在','data'=>array()));
        }
        else
        {
            //move_uploaded_file是php自带的函数，前面是旧的路径，后面是新的路径
            move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
            return json_encode(array('code'=>0,'msg'=>'success','data'=>array('file'=>'http://39.106.10.163:9599/static/file/'.$name)));
        }
        print_r($filename);

//        print_r(request()->file($_FILES['file']));
//        $files=request()->file($_FILES['file']);
//        $info=$files->move('upload');
//        print_r($info);
    }
}