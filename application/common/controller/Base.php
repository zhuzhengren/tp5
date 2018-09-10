<?php

namespace app\common\controller;

use think\Controller;
use think\facade\Session;

/**
 * 基础控制器
 * 必须继承自think\controller.php
 *
 * @author zzr
 */
class Base extends Controller {

    /**
     * 初始化方法
     * 创建一些常量，公共方法
     * 在所有的方法之前被调用
     * 
     */
    protected function initialize() 
    {
        
    }
    
    //防止重复登录
    public function isLogin()
    {
        if(Session::has('user_id')){
            $this->error('你已经登录','index/index');
        }
    }

}
