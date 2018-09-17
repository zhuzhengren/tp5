<?php

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\Article as ArtModel;
use app\admin\common\model\Cate as cateModel;
use think\facade\Request;
use think\facade\Session;

/**
 *  文章管理控制器
 *
 * @author zzr
 */
class Article extends Base {

    //文章管理的首页
    public function index() {
        //检查用户是否登陆
        $this->isLogin();

        //登陆成功后直接跳转到分类管理界面
        return $this->redirect('artList');
    }

    //文章列表
    public function artList() {
        //判断是否登陆
        $this->isLogin();
        
        //  获取用户的ID和用户的级别
        $userId = Session::get('admin_id');
        $isAdmin = Session::get('admin_level');
     
        
        //获取当前用户发布的文章
        $artList = ArtModel::where('user_id',$userId)->paginate(5);
        
        //如果超级管理员
        if($isAdmin==1){
            $artList = ArtModel::paginate(5);
        }
        
        //3.设置模板变量
        $this->view->assign("title", '文章管理');
        $this->view->assign("empty",'<span stype="color:red">没有文章</span>');
        $this->view->assign('artList', $artList);
        //渲染输出
        // halt($cateList);
        return $this->view->fetch('artlist');
    }

    //显示编辑页面
    public function artEdit() {
        //获取文章的ID
        $artId = Request::param('id');
        //2.根据主键查询文章信息
        $artInfo = ArtModel::where('id', $artId)->find();
        //获取栏目相关信息
        $cateList = cateModel::all();
        //3.设置模板变量
        $this->view->assign('title', "编辑文章");
        $this->view->assign('artInfo', $artInfo);
        $this->view->assign('cateList',$cateList);
        //4.渲染模板
        return $this->view->fetch('artEdit');
    }

    //  执行编辑操作
    public function doEdit() {
        //1.获取用户提交的信息
        $data = Request::param();

        //2。取出更新主键
        $id = $data['id'];

        //4.删除主键ID
        unset($data['id']);

        if (CateModel::where('id', $id)->data($data)->update()) {
            return $this->success('更新成功', 'cateList');
        }

        return $this->error('更新失败');
    }

    //执行删除操作
    public function doDelete() {
        $id = Request::param('id');
        //执行删除 其实是更新状态，设置为不可用
        if (CateModel::where('id', $id)->delete()) {
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }


}
