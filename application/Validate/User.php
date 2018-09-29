<?php

/**
 *
 * 用户信息表的字段验证器
 */

namespace app\validate;

class User extends \think\Validate {
    protected $rule = [
        'name|姓名'=>[
            'require' => 'require',
            'max'     => 20,
            'min'     => 5
        ],
        'email'=>[
            'require',
            'emial'=>'email',
        ],
        'password'=>[
            'require',
            'max'=>12,
            'min'=>3,
            'alphaNum'
        ],
        'mobile'=>[
            'require',
            'mobile'
        ]
        
        
    ];
}
