<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\admin\controller;

use app\admin\common\controller\Base;

/**
 * Description of index
 *
 * @author zzr
 */
class Index extends Base {

    //put your code here
    public function index() {

        //判断用户是否登陆
        $this->isLogin();

        return $this->redirect('user/userList');
    }

}
