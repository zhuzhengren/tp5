<?php


namespace app\index\controller;

use think\Controller;
use think\facade\View;

/**
 * PHP中文网
 * http://www.php.cn/code/25226.html
 *
 * @author zzr
 */
class Demo7 extends Controller{
    public function test1()
    {
        $content = '<h3 style="color:green">PHP中文网欢迎您</h3>';
        //第一种方式 直接将内容输出到页面，不通过模板
        //return $this->display($content);
        //第二种方式 通过view属性操作
        //return $this->view->display($content);
        //第三种方式 静态代理类
        return View::display($content);
    }
    
    //使用试图将数据进行输出
    public function test2()
    {
        /**
         * 模板变量赋值：assign()
         * 1。普通变量
         */
        $this->view->assign('name','wang');
        $this->view->assign('age','25');
        //批量赋值
        $this->view->assign([
            'sex'=>'man',
            'salary'=>8888
        ]);
        //数组
        $this->view->assign('goods',[
            'id'=>1,
            'name'=>'手机',
            'model'=>'mate9',
            'price'=>999
        ]);
        
        //对象
        $obj = new \stdClass();
        $obj->course = 'php';
        $obj->lecture = 'wang yan';
        $this->view->assign('info',$obj);
        
        //常量
        define("SITE_NAME",'zhu website');
        
        
        //在模板中输出数据
        //模板默认的目录位于当前木块的view目录，模板文件默认位于以当前
        return $this->view->fetch();
    }
    
    public function test3()
    {
        $data = \app\index\model\websites::all();
        $this->view->assign('data',$data);
        return $this->view->fetch();
    }
    
    public function test4()
    {
        //获取分页要调用查询类中的poginate(num)方法
        $data = \app\index\model\websites::paginate(5);
        $this->view->assign('data',$data);
        return $this->view->fetch();
    }
    
}
