<?php

/**
 * facade静态代理
 * 所谓静态代理就是建立一个马甲类，调用其他的类
 */


namespace app\index\controller;

use app\facade\Test;

class Demo2 {
    public function index($name="Thinkphp")
    {
        /**
         * 经典调用方式
         * 动态方式
         */

//        $test = new \app\common\Test();
//        return $test->hello($name);
        
        /**
         * 如果想静态调用一个动态方法，需要给当前类绑定一个静态代理类
         * app\facade\Test 静态代理类
         * 使用bind方法进行动态绑定
         */
     //   \think\Facade::bind('app\facade\Test','app\common\Test');
        return \app\facade\Test::hello("wang yan");
        
        
        
    }
}
