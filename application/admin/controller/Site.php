<?php

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\Site as SiteModel;
use think\facade\Request;
use think\facade\Session;


class Site extends Base{
    
    public function index(){
        //1。获取站点信息
        $siteInfo = SiteModel::get(['status'=>1]);
        //模板赋值
        $this->view->assign("siteInfo",$siteInfo);
       // halt($siteInfo);
        
        return $this->view->fetch('index');
    }

    public function save(){
        //获取数据
        $data = Request::param();
        //更新操作
        //halt($data);
        if(SiteModel::update($data)){
            $this->success("更新成功");
        }
        $this->error('更新失败');
    }
}