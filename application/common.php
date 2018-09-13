<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Db;

// 应用公共文件
//根据用户ID查询用户名称
if (!function_exists('getUserNameById')) {

    function getUserNameById($id) {
        return Db::table('zh_user')->where('id', $id)->value("name");
    }

}

//过滤文章内容
function getArtContent($content) {
    return mb_substr(strip_tags($content), 0, 50) . '>>>';
}



// 应用公共文件
//根据用户ID查询用户名称
if (!function_exists('getCateName')) {

    function getCateName($id) {
        return Db::table('zh_article_category')->where('id', $id)->value("name");
    }

}