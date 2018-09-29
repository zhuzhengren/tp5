<?php

/**
 * 用户自定义模型
 *
 * @author zzr
 */

namespace app\common\model;

use think\Model;

class Comment extends Model {

    protected $pk = 'id'; //默认主键
    protected $table = 'zh_user_comments'; //数据源
    //定义时间戳字段名：默认为create_time,如果一直可以省略
    //如果想关闭莫哥时间戳字段，将他置为false即可：$create_time=false
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createtime = 'create_time'; 
    protected $updatetime = 'update_time';
    protected $dateFormat = 'Y年m月d日'; //时间字段取出后的默认时间格式

    //开启自动设置
    protected $auto = []; //无论是新增还是更新都要设置的字段
    
    //仅新增有效
    protected $insert = ['create_time','status'=>1];
    
    //仅更新的时候设置
    protected $update = ['update_time'];
    
    
    
}
