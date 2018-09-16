<?php

/**
 * 后台公共控制器
 */
namespace app\admin\common\controller;

use think\Controller;
use think\facade\Session;

class Base extends Controller {
    protected function initialize() {
        
    }

    /**
     * 检查用户是否登陆
     * 1.调用位置在后台入口：admin:index.php
     */
    protected function isLogin(){
        if(!Session::has('admin_id')){
            $this->error("你还没有登陆",'admin/user/login');
        }
    }
    
}
