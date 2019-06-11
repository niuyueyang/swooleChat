<?php
/**
 * Created by PhpStorm.
 * User: niuyueyang
 * Date: 2019/3/5
 * Time: 9:35
 */
require_once './b.php';
class A{
    public $name='jack';
    private $age='15';
    public static $sex='男';
    public $b;
    public function __construct(){
        $this->name='mike';
        $this->b=new B();
    }

    public function test(){
       //return $this->name.' '.A::$sex;    //mike 男
       // return $this->name.' '.self::$sex;  //mike 男（直接输出可以self::$sex或者A::$sex，不同方法间调用只能通过非静态输出$this）
        $nameTest=$this->test2();
        return $nameTest;
    }
    public static function test1(){
        return A::$sex; //静态方法无法调用非静态方法及其变量，只能通过self::静态变量名/静态方法（类名::静态变量名/静态方法）
    }
    public function test2(){
        //当另一个方法调用时，非静态变量及方法在本方法里面返回只能$this，静态变量及方法可以使用self::变量名/方法
        //return self::$sex;    //男
       // return self::test1(); //男
        //return $this->b->b2; //我是B中可以修改得变量
        //return $this->b->b1;//由于b1在B中是protected类型，不可以在这里返回，所以直接输出错误
//        $this->b->b2='我现在在A中被修改了';
//        return $this->b->b2; //我现在在A中被修改了
        return 111;

    }
}
$a=new A();
echo $a->test().PHP_EOL;
echo A::test1().PHP_EOL;