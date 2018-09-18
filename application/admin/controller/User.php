<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;

/**
 * Description of User
 *
 * @author zzr
 */
class User extends Base {

    //渲染登陆界面
    public function login() {
        $this->view->assign('title', '管理员登陆');
        return $this->view->fetch('login');
    }

    //验证后台登陆
    public function checkLogin() {
        $data = Request::param();
        $map[] = ['email', '=', $data['email']];
        $map[] = ['password', '=', sha1($data['password'])];
        $map[] = ['status','=',1];
        $result = UserModel::where($map)->find();
        if ($result) {
            //兼容一下后台登陆，前台登陆同步
            Session::set('user_id',$result['id']);
            Session::set('user_name',$result['name']);
            
            Session::set('admin_id', $result['id']);
            Session::set('admin_name', $result['name']);
            Session::set('admin_level', $result['is_admin']);
            
            $this->success('登陆成功', 'admin/user/userlist');
        }
        $this->error("登陆失败");
    }

    //退出登陆
    public function logout() {
        //清除session
        Session::clear();
        //
        $this->success('退出成功', 'admin/user/login');
    }

    //返回后台管理的用户列表
    public function userList() {
        //1。获取到当前用户的ID，特别是is_admin
        $data['admin_id'] = Session::get('admin_id');
        $data['admin_level'] = Session::get('admin_level');
        //获取当前用户的信息
        $userList = UserModel::where('id', $data['admin_id'])->select();
        //如果是超级管理员，则返回全部用户的信息
        if ($data['admin_level'] == 1) {
            $userList = UserModel::select();
        }
        //模板赋值
        $this->view->assign('title', '用户管理');
        $this->view->assign('empty', '<span style="color:red">没有任何数据</span>');
        $this->view->assign('userList', $userList);

        //渲染输出
        return $this->view->fetch('userList');
    }

    //渲染编辑用户的界面
    public function userEdit() {
        //1.获取要更新的用户的主键
        $userId = Request::param('id');
        //2.根据主键进行查询
        $userInfo = userModel::where('id', $userId)->find();
        //3。设置编辑界面的模板变量
        $this->view->assign('title', '编辑用户');
        $this->view->assign('userInfo', $userInfo);
        //4.渲染出编辑界面
        return $this->view->fetch('useredit');
    }

    //接受请求信息，修改用户数据
    public function doEdit() {
        //1.获取用户提交的信息
        $data = Request::param();

        //使用验证器类判断数据
        $rule = [
            'name|用户名' => "require|length:5,20|chsAlphaNum",
            'email|邮箱' => "require|email|unique:zh_user",
            'mobile|手机号' => "require|mobile|unique:zh_user",
            'password|密码' => "require|length:6,20|alphaNum"
        ];
        $res = $this->validate($data, $rule);
        if (true !== $res) {
            return $this->error($res);
        }
        //2。取出用户主键
        $id = $data['id'];
        //防止调用接口
//        if(($id == Session::get('admin_id')) || (1!== Session::get('admin_level'))){
//            halt([$id,Session::get('admin_id'),Session::get('admin_level')]);
//            return $this->error('没有权限修改他人信息');
//        }

        //3。将用户密码加密
        $data['password'] = sha1($data['password']);

        //4.删除主键ID
        unset($data['id']);

        //5.判断数据是否重复
        $res1 = UserModel::where('email', $data['email'])->where('id', '<>', $id)->find();
        $res2 = UserModel::where('mobile', $data['mobile'])->where('id', '<>', $id)->find();
        if ($res1) {
            return $this->error('邮箱已被占用');
        }
        if ($res2) {
            return $this->error('手机已被占用');
        }

        if (UserModel::where('id', $id)->data($data)->update()) {
            return $this->success('更新成功', 'userList');
        } else {
            return $this->error('更新失败');
        }
    }
    
    //执行删除操作
    public function doDelete(){
        $id = Request::param('id');
        //执行删除 其实是更新状态，设置为不可用
        if(UserModel::where('id',$id)->data(['status'=>0])->update()){
            return $this->success('删除成功');
        }
        else{
            return $this->error('删除失败');
        }
    }

}
