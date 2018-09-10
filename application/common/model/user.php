<?php

/**
 * 用户自定义模型
 *
 * @author zzr
 */

namespace app\common\model;

use think\Model;

class user extends Model {

    protected $pk = 'id'; //默认主键
    protected $table = 'zh_user'; //数据源
    protected $autoWriteTimestamp = true; //自动时间戳
    protected $createtime = 'create_time';
    protected $updatetime = 'update_time';
    protected $dateFormat = 'Y年m月d日';

    //获取器
    //明确表示字段的含义
    //get字段名Attr 参数是该字段的值
    public function getStatusAttr($value) {
        $status = [1 => '启用', 0 => '禁用'];
        return $status[$value];
    }

    public function getIsAdminAttr($value) {
        $status = [1 => '管理员', 0 => '普通会员'];
        return $status[$value];
    }

    //修改器
    
    public function setPasswordAttr($value)
    {
        return sha1($value);
    }
    
}
