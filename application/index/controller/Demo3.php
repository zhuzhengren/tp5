<?php



namespace app\index\controller;

//use think\facade\Request;

/**
 * php中文网
 * http://www.php.cn/code/25136.html
 * 正常情况下，控制器不依赖于父类controller.php
 * 推荐继承父类，可以很方便的使用父类中封装好的一些方法和属性
 *Controller没有静态代理
 * 控制器中的输出推荐使用return返回，不要使用echo
 * 如果输出的是复杂类型，我们可以用dump（）函数
 * dump()可以理解成格式化的print_r()
 * 默认输出的格式为html，可以指定为其他格式：json
 */
class Demo3 extends \think\Controller 
{
    public function test()
{
        /**
         * 创建一个请求对象Request的静态代理 
         * 有四种方式使用
         * 1。传统的动态方式
         * $request = new \think\Request();
         *  dump($request->get());
         *2。静态代理
         * think\facade\Request
         * 3.依赖注入 Request $request
         * 4。父类属性
         * $this->request->get()
         * 
         */
        //使用传统方式 demo2
        //$request = new \think\Request();
        //使用依赖注入
        //dump($request->get());
        //因为函数继承了controller类，
        
        //dump($this->request->get());
        return json_encode($this->request->get());
        
        
    }
}
