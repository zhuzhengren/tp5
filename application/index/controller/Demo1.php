<?php


namespace app\index\controller;

/**
 * 容器和依赖注入的原理
 * ---------------------------------
 * 1.任何的URL访问最终都是定位到控制器的，有控制器中的某个具体的方法执行
 * 2。一个控制器对应一个类，如果这些类需要进行统一管理，怎么办？
 * 使用容器进行类管理，还可以将类的实例作为参数，传递给类方法，自动触发以来注入
 * 依赖注入：将对象类型的数据以参数的方式传到方法的参数列表中
 * URL访问：通过get方式将数据传到控制器指定的方法中
 * http://localhost:8888/index/demo1/getname/name/thinkphp51
 * 参数通过斜线传递或者？=
 * 参数只能传递字符串 数值，不能传递对象（需要通过依赖注入）
 * 
 * 
 */




class Demo1 {
    public function getName($name='zhu'){
        return "hello  ".$name;
    }
    
    //依赖注入
    //\app\common\Temp $tmp 等价于 $temp = new  \app\common\Temp
    public function getMethod(\app\common\Temp $temp){
       // $temp = new \app\common\Temp;
        $temp->setName("wang yan ");
        return $temp->getName();
    }
    //绑定一个类到容器中
    public function bindClass(){
        //把一个类放到容器中，相当于注册到容器中
        \think\Container::set('temp','\app\common\Temp');
        //将容器中的类实例化并且取出来用，实例化时可同时调用构造起初始化
        $temp = \think\Container::get('temp',['name'=>'wang']);
        
        return $temp->getName();
    }
    
    //绑定一个闭包到容器,闭包可以暂时理解为匿名函数
    public function bindClosure()
    {

        //把一个闭包放到容器中，相当于注册到容器中
        \think\Container::set('demo',function($domain='lll'){
            return "朱正仁最爱的人是谁？？".$domain;
        });

        //将容器中的闭包并且取出来用，实例化时可同时调用构造起初始化
        return \think\Container::get('demo',['domain'=>'wang yan']);
        
        
    }
}
