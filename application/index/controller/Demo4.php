<?php


namespace app\index\controller;
use think\Db;
/**
 * php中文网
 * http://www.php.cn/code/25223.html
 * 
 * 连接数据库
 * 1。全局配置：config/database.php
 * 2.动态配置：
 * 3.DSN连接：数据库类型：//用户名：密码@数据库地址：端口号/数据库名称#字符集
 * 
 * 
 */
class Demo4 {
    //全局配置
    public function conn1()
    {
       dump(Db::table('websites')
                ->where('id',1)
                ->selectOrFail());
    }
    //动态配置
    public function conn2()
    {
        return Db::connect([
            'type'=>'mysql',
            'hostname'=>'123.207.140.186',
            'database'=>'myDB',
            'username'=>'root',
            "password"=>''  
        ])
                ->table('websites')
                ->where('id',2)
                ->value('name');
    }
    //DSN连接
    public function conn3()
    {
        $dsn = 'mysql://root:root@123.207.140.186:3306/myDB#utf8';
        return Db::connect($dsn)
                ->table('websites')
                ->where('id',4)
                ->value('name');
    }
    
}
