<?php


namespace app\admin\controller;

//use think\facade\Config;

/**
 * Description of User
 *
 * @author zzr
 */
class User {
    
    public function get(){
        //获取全部的配置项
      //  dump(Config::get());
        //获取部分的配置项
     //   dump(Config::get("app."));
        //获取一级配置项
     //   dump(Config::pull('app'));
        //获取二级配置项
    //    dump(Config::get("app.app_debug"));
        //获取当前的语言
        dump(Config::get("default_lang"));
        //判断配置是否存在
        dump(Config::has("default_lang"));
        //查询database 的配置项
        dump(Config::get("database.hostname"));
    }
    
    public function set(){
        dump(Config::get("app_debug"));
        dump(Config::set("app_debug",true));
    }
    
    public function helper(){
        //dump(config());
       // dump(config('default_module'));
        //带？号就是查询是否存在
        dump(config('?database.username'));
        //查询值
        dump(config('database.username'));
        //设置
        config('database.hostname','localhost');
        dump(config('database.hostname'));
    }
}
