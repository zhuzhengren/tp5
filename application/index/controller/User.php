<?php

namespace app\index\controller;
use think\Db;
use app\common\controller\Base;
use think\facade\Request;
use app\common\model\User as userModel;
use think\facade\Session;
/**
 * Description of User
 *
 * @author zzr
 */
class User extends Base {

    public function register() {
        $this->is_reg();
        $this->assign('title', '用户注册');
        return $this->fetch();
    }

    //处理用户提交的注册信息
    public function insert() {
        if (Request::isAjax()) {
            //获取用户通过表单提交过来的数据，过滤password_confirm
            //$data = Request::except('password_comfirm','post');
            $data = Request::post();
            $rule = 'app\common\validate\User';
            $res = $this->validate($data, $rule);
            //此处要用恒等于
            if (true !== $res) {
                return ['status' => -1, "message" => $res];
            } else {
		if ($user = userModel::create($data)) {
//		$data=["name"=>"zhuzhengren","email"=>"99@qq.com","status"=>0,"mobile"=>"15555555559","password"=>"ssssssssss"];
//		unset($data["password_confirm"]);
//		$data["create_time"]=time();
//		$data["update_time"]=time();
//		$data["password"]=md5($data["password"]);
//		if($id=Db::table('zh_user')->insertGetId($data)){
                  //create()返回的是用户的模型
                    //实现注册成功后自动登陆
                    $userinfo = userModel::get($user->id);
           		Session::set('user_name',$userinfo->name);
                    Session::set('user_id',$userinfo->id);


//                    $userinfo = Db::table("zh_user")->where("id",$id)->select();  
//  		    Session::set('user_name',$userinfo[0]["name"]);
//                    Session::set('user_id',$userinfo[0]["id"]);
                    return ['status' => 1, 'message' => "注册成功"];
                } else {
                    return ['status' => 0, 'message' => $res];
                }
            }
        } else {
            $this->error('请求类型错误', 'register');
        }
    }

    //用户登陆
    public function login() {
        $this->isLogind();
        return $this->view->fetch('login', ['title' => '用户登录']);
    }

    //登陆验证
    public function loginCheck() {
        if (Request::isAjax()) {
            $data = Request::post();
		$rule = [
                "email|邮箱"=>'require|email',
                'password|密码'=>'require|alphaNum'
            ];
            
            $res = $this->validate($data, $rule);
            //此处要用恒等于
            if (true !== $res) {
                return ['status' => -1, "message" => $res];
            } else {
                //执行查询
		$result = Db::table('zh_user')->where('email',$data['email'])->where('password',sha1($data['password']))->select();
                if (null == $result) { 
                    return ['status' => 0,'message' => '邮箱或者密码不正确'];
                } else {
                    //将用户的数据写到session中
		   Session::set('user_id',$result[0]['id']);
		   Session::set('user_name',$result[0]['name']);
                   return ['status' => 1, 'message' => '恭喜,登录成功'];
          }}
        } else {
            $this->error('请求类型错误', 'index/index');
        }
    }
    
    //退出登录
    public function logout()
    {
        Session::clear();
        $this->success('成功退出','index/index');
    }

}
