<?php

namespace app\common\controller;

use think\Controller;
use think\facade\Session;
use app\common\model\ArticleCate;

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
        //显示分类导航
        $this->showNav();
        
    }
    
    //防止重复登录
    public function isLogind()
    {
        if(Session::has('user_id')){
            $this->error('你已经登录','index/index');
        }
    }
    
        //检查是否登陆，用于需要登陆的操作执行前
    public function isLogin()
    {
        if(!Session::has('user_id')){
            $this->error('对不起，您还没有登陆','user/login');
        }
    }
    
    //显示分类导航
    public function showNav(){
        //1.查询分类表，获取所有的分类信息
        $cateList = ArticleCate::all(function($query){
            $query->where('status',1)->order('sort','asc');
        });
        //将变量信息赋值给模板，nac.html
        $this->view->assign('cateList',$cateList);
        
    }
    
    

}
