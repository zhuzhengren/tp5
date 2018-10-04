<?php

namespace app\wx\controller;

class Index extends \think\Controller {

    public function index() {
        //timestamp=1231231231&nonce=zhu&token=weixin&signature=3ebfeff432cd4126af6f72025202f91ea9ffe31a&echostr=hello
        
        //将timestamp，nonce,token按字典排序
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'weixin';
        $signature = $_GET['signature'];
        $array = array($timestamp, $nonce, $token);
        sort($array);
        //将排序后的三个参数拼接之后使用sha1加密
        $tmpstr = implode('', $array);
        echo $tmpstr;
        $tmpstr = sha1($tmpstr);
        //将加密后的字符串与signature进行对比，怕奴蛋该请求是否来自微信
        if ($tmpstr == $signature) {
            echo $_GET['echostr'];
            exit;
        }
    }
}
