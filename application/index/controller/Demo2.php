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
         * 
         * 
         * 静态代理的步骤
         * 1。在同级目录下的facade 的建立相同的一个门面类，该类继承think\Facade
         * 2。将门面类与用户自定义的类进行绑定
         * 2-1。静态绑定 protected static function getFacadeClass()
         * 2-2。动态绑定 \think\Facade::bind('app\facade\Test','app\common\Test');
         * 3。在当前类（一般control类）中倒入门面类 use app\facade\***;
         * 4。直接使用门面类的静态方法操作 Test::hello("wang yan")
         */
        \think\Facade::bind('app\facade\Test','app\common\Test');
        return Test::hello("wang yan");
        
        
        
    }
}
