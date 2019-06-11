<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/4
 * Time: 9:06
 */
//创建内存表，共有1024行
$table=new swoole_table(1024);
//内存表设置字段
$table->column('id',$table::TYPE_INT,8);
$table->column('name',$table::TYPE_STRING,64);
$table->column('age',$table::TYPE_INT,8);
$table->create();


$table->set('imooc',['id'=>1,'name'=>'imooc','age'=>18]);

//对字段自加
$table->incr('imooc','age',2);

//对字段自减
$table->decr('imooc','age',2);
var_dump($table->get('imooc'));

//检测是否存在
var_dump($table->exist('imooc'));
//删除字段
$table->del('imooc');
var_dump($table->get('imooc'));

