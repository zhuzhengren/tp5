<?php

namespace app\index\controller;

use think\Controller;
//用户自定义验证器
//use app\validate\User;
//使用静态代理类
use app\facade\User;
//使用静态调用
use think\facade\Validate;

/**
 * PHP有两种验证方式
 * 1。验证器
 * 2。独立验证
 * 
 * 验证器总结
 * 1。验证器是一个自定义的类，继承于框架中的验证类think\Validate.php
 *  本文实例app\validate\user.php 中user类继承了Validate
 * 2.验证器可以创建在应用application目录下的任何一个可以访问的目录下面，
 * 这个访问时止控制器可以访问，并不是指外部的URL访问，只需要制定正确的命名空间
 * 3。验证器其实就是完成框架think\Validate.php protected $rule[]初始化
 * 4.在控制器总直接实例化调用check()完成验证
 * 5。还可以创建一个自定义的静态代理，来统一验证方法的调用方式
 * 
 */
class Demo9 extends Controller {

    //1.验证器：使用Validate类中的属性rule
    public function test1() {
        //要验证的数据
        $data = [
            'name' => "zhuzhengren",
            'email' => "zhuzr@yilan.tv",
            'password' => '12345678',
            'mobile' => '183104#06456'
        ];

        //验证器是一个类
//        $validate = new User;
//        if(!$validate->check($data)){
//            return $validate->getError();
//        }
//        return '验证通过';
        //使用当前验证器的静态代理
        if (!User::check($data)) {
            return User::getError();
        }
        return '验证通过';
    }

    //调用控制器中validate方法进行验证
    public function test2() {
        /**
         * $this->validate($data, $validate);
         * 返回验证结果
         * $data 验证数据
         * $validate验证规则 其实就是当前User类
         */
        $data = [
            'name' => "zhuzhengren",
            'email' => "zhuzr@yilan.tv",
            'password' => '12345678',
            'mobile' => '18310406456'
        ];

        $validate = 'app\validate\User';
        $res = $this->validate($data, $validate);
        if (true !== $res) {
            return $res;
        }
        return '验证通过';
    }

    /**
     * 独立验证：使用验证器类\think\Validate中的rule（）方法
     * rule()方法实际上就是完成当前类的protected $rule[]的初始化
     * 独立验证就是不依赖于用户自定义的验证器类
     */
    public function test3() {
        //创建验证规则
        $rule = [
            'name|姓名' => [
                'require' => 'require',
                'max' => 20,
                'min' => 5
            ],
            'email' => [
                'require',
                'emial' => 'email',
            ],
            'password' => [
                'require',
                'max' => 12,
                'min' => 3,
                'alphaNum'
            ],
            'mobile' => [
                'require',
                'mobile'
            ]
        ];
        $data = [
            'name' => "zhuzhengren",
            'email' => "zhuzr@yilan.tv",
            'password' => '12345678',
            'mobile' => '18310406456'
        ];

        //添加字段的验证规则：初始化rule属性
        Validate::rule($rule);
        if (!Validate::check($data)) {
            return Validate::getError();
        }
        return "验证通过";
    }

}
