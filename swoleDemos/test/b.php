<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/5
 * Time: 9:35
 */
class B{
    protected $b1='我是B中不可修改的变量';
    public $b2='我是B中可以修改得变量';
    public $b3='我是B中方法返回的变量';
    public function bTest(){
        return $this->b3;
    }
}