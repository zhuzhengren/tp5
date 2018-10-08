<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\edu\controller;

use think\Controller;

class Index extends Base {

    public function index() {
        return $this->view->fetch();
    }

}
