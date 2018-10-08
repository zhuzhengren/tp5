<?php

namespace app\edu\controller;

use think\Controller;
use think\facade\Request;
use app\edu\model\edu_user as UserModel;
/**
 * Description of User
 *
 * @author zzr
 */
class User extends Controller {

    public function login() {
        return $this->view->fetch();
    }

    public function checkLogin() {
        $data = Request::param();
        
        $rule = [
            'username|用户名'=>'require',
            'password|密码'=>'require',
            'code|验证码'=>'require|captcha',
        ];
        $result=$this->validate($data, $rule);
        if($result === true){
            $map=[
                'name' => $data['username'],
                'password'=>md5($data['password'])
            ];
            $user = UserModel::get($map);
            if($user ==  null){
                return ["status" => 1,"result"=> "没有找到该用户"];
            }
            else{
                return ["status" => 1,"result"=> "恭喜登陆成功"];
            }
        }
        return ["status" => 0,"result"=> $result];
    }

    public function logout() {
        
    }

}
