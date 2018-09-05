<?php

namespace app\facade;

class Test extends \think\Facade
{
    /**
     * 此处进行静态绑定
     * 注释这里，可以使用bind函数进行动态绑定
     */
//    protected static function getFacadeClass() {  
//        //返回当前类所代理的类
//        //关键 返回了一个类！！！！！
//        return 'app\common\Test';
//    }
    
    //这种方式不行？？？
//    protected static function getFacadeClass() {
//        parent::getFacadeClass();
//    }
    
    
}
