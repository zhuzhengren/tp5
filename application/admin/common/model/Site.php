<?php

/**
 * Description of User
 *
 * @author zzr
 */

namespace app\admin\common\model;

use think\Model;

class Site extends Model {
    
    protected $pk = 'id';
    protected $table = 'zh_site';
    
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createtime = 'create_time';
    protected $updatetime = 'update_time';
   // protected $dateFormat = 'Y年m月d日'; //时间字段取出后的默认时间格式
    //开启自动设置
    protected $auto = []; //无论是新增还是更新都要设置的字段
    //仅新增有效
    protected $insert = ['create_time', 'status' => 1, 'is_open' => 1, 'is_reg' => 0];
    //仅更新的时候设置
    protected $update = ['update_time'];
    
}
