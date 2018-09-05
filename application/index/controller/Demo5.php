<?php

namespace app\index\controller;

use think\Db;
/**
 * 查询构造器
 * 准备工作：app_debug=>'true';app_trace=>'true'   config\app.php
 * 系统学习数据库的CURD
 *  
 */
class Demo5 {
    //单条查询
    public function find()
    {
        /**
         * Db类是数据库的操作入口类
         * 功能：静态调用think\db\query.php类中的查询方法实现基本操作
         * table():选择数据表
         * where():设置查询条件， 表达式 ，数组
         * filed()用来设置返回的字段
         * 1。对于单个条件使用表达式
         * 2。对于多个条件使用数组表达式
         * find()返回符合条件的第一条记录
         */
        $res = Db::table('websites')
                //->field('name,url')
                ->field(['name'=>"名字",'url'=>'地址'])
                //->where('id','>',3) //如果是相等关系，可以省略=号
                ->find(3); //如果是主键查询，可省略where
        dump(is_null($res)?'没有找到':$res);
        
        
    }
    //多条查询
    public function select()
    {
        $res = Db::table("websites")
                ->field('name,url')
                ->where([['id','>',1],
                        ['name','<>','淘宝']])
                ->select();
        if(empty($res))
        {
            return '没有满足条件的记录';
        }
        else
        {
            foreach($res as $row)
            {
                dump($row);
            }
        }
    }
    //单条插入
    public function insert()
    {
        $data = [
            'name'=>'zhu2',
            'url'=>'www.wantwin.xyz',
            'alexa'=>'9998',
            'country'=>'CN'
        ];
        //方式1
        //return Db::table('websites')->insert($data);
        //INSERT INTO `websites` (`name` , `url` , `alexa` , `country`) VALUES ('zhu2' , 'www.wantwin.xyz' , 9998 , 'CN') 
        
        //方式2
        ////只有数据库类型为mysql是，true才可以传入
        //return Db::table('websites')->insert($data,true);
        //REPLACE INTO `websites` (`name` , `url` , `alexa` , `country`) VALUES ('zhu2' , 'www.wantwin.xyz' , 9998 , 'CN') 
    
        //方式3
        //return Db::table('websites')->data($data)->insert();
        
        //方式4
        //插入的同时返回主键ID
        return Db::table('websites')->insertGetId($data);
        
    }
    
    //多条插入
    public function insertAll()
    {
        $data =[
            ['name'=>'zhu3','url'=>'www.wantwin.xyz','alexa'=>'9998','country'=>'CN'],
            ['name'=>'zhu4','url'=>'www.wantwin.xyz','alexa'=>'9998','country'=>'CN']
        ];
        return Db::table('websites')->insertAll($data);
    }
    
    //更新操作
    public function update()
    {
        //更新操作必须要有更新条件
//        return Db::table('websites')
//                ->where('id','>','10')
//                ->update(['name'=>'wang']);
        
        return Db::table('websites')
                ->update(['name'=>'meng','id'=>15]);        
    }
    
    //删除操作
    public function delete()
    {
        return Db::table('websites')
                ->delete(15);
    }
    
    //原生查询
    public function query()
    {
        $sql = "SELECT `name`,`url` FROM `websites` where `id` IN(2,3,4)";
        dump(Db::query($sql));
    }
    
    //原生写操作:更新，删除，添加
    public function execute()
    {
        //更新
       //return  Db::execute("UPDATE `websites` SET `url`='www.baidu.com' WHERE `id`>10");
        //插入
        //return  Db::execute("INSERT INTO `websites` SET `url`='www.baidu.com'");
       //删除
        return  Db::execute("DELETE FROM `websites` WHERE `id`>15");  
    } 
    
}
