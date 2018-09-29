<?php


namespace app\index\controller;

use think\Controller;
/**
 * Description of Demo8
 *
 * @author zzr
 */
class Demo8  extends Controller{
    
    public function test1()
    {
        return $this->view->fetch();
    }
    
    //模板继承
    public function test2()
    {
        return $this->view->fetch();
    }
}
