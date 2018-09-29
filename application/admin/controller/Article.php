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
        $artList = ArtModel::where('user_id', $userId)->paginate(5);

        //如果超级管理员
        if ($isAdmin == 1) {
            $artList = ArtModel::paginate(5);
        }

        //3.设置模板变量
        $this->view->assign("title", '文章管理');
        $this->view->assign("empty", '<span stype="color:red">没有文章</span>');
        $this->view->assign('artList', $artList);
        //渲染输出
        // halt($cateList);
        return $this->view->fetch('artList');
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
        $this->view->assign('cateList', $cateList);
        //4.渲染模板
        return $this->view->fetch('artEdit');
    }

    //  执行文章的编辑操作
    public function doEdit() {
        //获取上传的信息
        $data = Request::param();
        //获取图片信息
        //验证成功
        //获取一下图片的信息
        $file = request()->File('title_img');
        //文件信息验证成功后，在上传到服务器指定的目录，以public为起始目录
        $info = $file->validate([
                    'size' => 1024 * 1024,
                    'ext' => 'jpeg,jpg,png,git',
                ])->move("uploads");

        if ($info) {
            //上传成功
            //用户上传到服务器之后的名字
            //getSaveName()全路径 getFileName()文件名

            $data['title_img'] = $info->getSaveName();
            //    echo '<script>alert("' . $info->getFileName() . '");location.back();</script>';
        } else {
            $this->error($file->getError());
        }

        //  保存文章信息
        if (ArtModel::update($data)) {
            $this->success("文章更新成功", 'artList');
        } else {
            $this->error("文章更新失败");
        }
    }

    //执行文章的删除操作
    public function doDelete() {
        //halt(Request::param());
        $artId = Request::param("id");
        
        if(ArtModel::where('id',$artId)->update(['status'=>'0'])){
            $this->success("删除成功");
        }
        $this->error("删除失败");
        
    }

}
