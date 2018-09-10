<?php

/**
 * 用户自定义验证器
 * zh_user验证器
 *
 * @author zzr
 */

namespace app\common\validate;

use think\Validate;

class ArticleCate extends Validate{
    
    protected $rule =[
        'name|栏目名称'=>"require|length:3,20|chsAlpha",
    ];
}
