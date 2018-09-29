<?php

namespace app\common\controller;

use think\Controller;
use think\facade\Session;
use app\common\model\ArticleCate;
use app\admin\common\model\Site;
use think\facade\Request;
use app\common\model\Article;

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
    protected function initialize() {
        //显示分类导航
        $this->showNav();
        //判断网站是否关闭
        $this->is_open();
        //获取右侧数据
        $this->getHot();
    }

    //防止重复登录
    public function isLogind() {
        if (Session::has('user_id')) {
            $this->error('你已经登录', 'index/index');
        }
    }

    //检查是否登陆，用于需要登陆的操作执行前
    public function isLogin() {
        if (!Session::has('user_id')) {
            $this->error('对不起，您还没有登陆', 'user/login');
        }
    }

    //显示分类导航
    public function showNav() {
        //1.查询分类表，获取所有的分类信息
        $cateList = ArticleCate::all(function($query) {
                    $query->where('status', 1)->order('sort', 'asc');
                });
        //将变量信息赋值给模板，nac.html
        $this->view->assign('cateList', $cateList);
    }

    //检查网站是否关闭
    public function is_open() {
        //获取站点状态
        $isOpen = Site::where('status', 1)->value('is_open');
        //如果站点已经关闭，那么我们只允许关闭前台，后台不能关闭
        if ($isOpen == 0 && Request::module() == 'index') {
            //关闭网站
            $info = ' <body>
                   <h1 style="color:#eee;text-align:center;margin:200px">站点维护中</h1>
                   </body> ';

            exit($info);
        }
    }

    //检测网站是否允许注册
    public function is_reg() {
        //获取注册状态
        $isReg = Site::where('status', 1)->value('is_reg');
        //如果站点已经关闭，那么我们只允许关闭前台，后台不能关闭
        if ($isReg == 0) {
            $this->error('注册关闭', 'index/index');
        }
    }
    
    //更加pv获取排行
    public function getHot(){
        
       $hotArtList =  Article::where('status',1)->order(['pv'=>'desc'])->limit(12)->select();
        
        $this->view->assign('hotArtList',$hotArtList);
        
    }

}
