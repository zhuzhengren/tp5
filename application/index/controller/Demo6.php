<?php

/*
 * 模型一定是和数据表绑定的
 * 在目录下建立一个model目录，然后建立一个和表同名的类，该类继承Model类
 * 神了！！！！
 */

namespace app\index\controller;

use app\index\model\websites;
use think\Db;


class Demo6 {
    
    public function get()
    {
        dump(websites::get(3));
        
        //用查询构造器创建更加复杂的查询
        $res = websites::field('name,url')
                ->where('id','2')
                ->find();
        
        $res = Db::table('websites')
                ->field('name,url')
                ->where('id','2')
                ->find();        
        
        dump($res);
    }
    
    
    public function getAll()
    {
        //dump(websites::all());
        
        //用查询构造器创建更加复杂的查询
        $res = websites::field('name,url')
                ->where('id','>','2')
                ->select();
        
        dump($res);
    }
}
