<?php


/**
 * 测试专用控制器
 *
 * @author zzr
 */

namespace app\index\controller;

use app\common\model\user;

use app\common\controller\Base;

use think\facade\Session;

class Test extends Base {
    //测试用户的验证器
    public function test1()
    {
        //此处传递的是ID主键
        dump(Session::clear());
    }
}
