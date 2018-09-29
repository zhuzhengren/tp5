<?php

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\Cate as CateModel;
use think\facade\Request;
use think\facade\Session;

/**
 * Description of Cate
 *
 * @author zzr
 */
class Cate extends Base {

//分类管理的首页
    public function index() {
        //检查用户是否登陆
        $this->isLogin();

        //登陆成功后直接跳转到分类管理界面
        return $this->redirect('cateList');
    }

    //分类列表
    public function cateList() {
        $this->isLogin();
        //获取所有分类
        $cateList = CateModel::all();

        //3.设置模板变量
        $this->view->assign("title", '分类管理');
        //  $this->view->assign("empty",'<span stype="color:red">没有信息</span>');
        $this->view->assign('cateList', $cateList);
        //渲染输出
        // halt($cateList);
        return $this->view->fetch('cateList');
    }

    //显示编辑页面
    public function cateEdit() {
        //获取分类的ID
        $cateId = Request::param('id');
        //2.根据主键查询用户信息
        $cateInfo = CateModel::where('id', $cateId)->find();
        //3.设置模板变量
        $this->view->assign('title', "编辑分类");
        $this->view->assign('cateInfo', $cateInfo);
        //4.渲染模板
        return $this->view->fetch('cateEdit');
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
    public function doDelete(){
        $id = Request::param('id');
        //执行删除 其实是更新状态，设置为不可用
        if(CateModel::where('id',$id)->delete()){
            return $this->success('删除成功');
        }
        else{
            return $this->error('删除失败');
        }
    }
    
    //添加分类,渲染添加界面
    public function cateAdd(){
        return $this->view->fetch('cateadd',['title'=>'添加分类']);
    }
    
    //添加分类
    public function doAdd(){
        //获取添加数据
        $data = Request::param();
        $data['user_id']= Session::get('admin_id');
        //执行新增
       // halt($data);
        if(CateModel::create($data)){
            $this->success('添加成功','cateList');
        }
        $this->error('添加失败');
    }

}
