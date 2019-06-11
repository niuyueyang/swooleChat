<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        print_r($_GET);
        return 'hello';
    }

    public function singwa(){
        echo time();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
