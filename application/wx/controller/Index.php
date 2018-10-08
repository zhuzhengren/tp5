<?php

namespace app\wx\controller;

use think\facade\Request;
use think\facade\Log;
use think\facade\Session;
use app\wx\common\IndexModel;

class Index extends \think\Controller {

    public function index() {
        //将timestamp，nonce,token按字典排序
        $data = Request::param();
        $timestamp = $data['timestamp'];
        $nonce = $data['nonce'];
        $token = 'weixin';
        $signature = $data['signature'];
        $array = array($timestamp, $nonce, $token);
        sort($array);
        //将排序后的三个参数拼接之后使用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        //将加密后的字符串与signature进行对比，怕奴蛋该请求是否来自微信
        if ($tmpstr == $signature && isset($data['echostr'])) {
            return $_GET['echostr'];
            exit;
        } else {
            return $this->responseMsg();
        }
    }

//测试账号的入口地址
    public function valid() {
        //将timestamp，nonce,token按字典排序
        $data = Request::param();
        $timestamp = $data['timestamp'];
        $nonce = $data['nonce'];
        $token = 'weixin';
        $signature = $data['signature'];
        $array = array($timestamp, $nonce, $token);
        sort($array);
        //将排序后的三个参数拼接之后使用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
        //将加密后的字符串与signature进行对比，怕奴蛋该请求是否来自微信
        if ($tmpstr == $signature && isset($data['echostr'])) {
            return $_GET['echostr'];
            exit;
        } else {
            return $this->responseMsg();
        }
    }

    //接收到用户关注的事件时，回复内容
    public function responseMsg() {
        //获取到微信推送过来的post数据（XML）格式
        $postArr = file_get_contents('php://input');
        /**
         * 微信推送的数据格式
         * <xml>
         * <ToUserName>< ![CDATA[toUser] ]></ToUserName>
         * <FromUserName>< ![CDATA[FromUser] ]></FromUserName>
         * <CreateTime>123456789</CreateTime>
         * <MsgType>< ![CDATA[event] ]></MsgType>
         * <Event>< ![CDATA[subscribe] ]></Event>
         * </xml>
         */
        //处理消息类型，并且设置回复类型和内容
        $postObj = simplexml_load_string($postArr);

        //判断该数据包是否是订阅事件的推送
        if (strtolower($postObj->MsgType) == 'event') {
            //关注事件
            if (strtolower($postObj->Event) == 'subscribe') {
                $content = "欢迎关注我的公众号";

                $indexModel = new IndexModel;
                return $indexModel->responseText($postObj, $content);
            }
            //自定义菜单点击事件
            if (strtolower($postObj->Event) == 'click') {
                /**
                 * 点击自定义菜单是的系统推送格式
                 * <xml>
                  <ToUserName><![CDATA[toUser]]></ToUserName>
                  <FromUserName><![CDATA[FromUser]]></FromUserName>
                  <CreateTime>123456789</CreateTime>
                  <MsgType><![CDATA[event]]></MsgType>
                  <Event><![CDATA[CLICK]]></Event>
                  <EventKey><![CDATA[EVENTKEY]]></EventKey>
                  </xml>
                 */
                if ($postObj->EventKey == 'V1001_TODAY_MUSIC') {
                    $content = "不好意思，暂时没有歌曲";
                    $indexModel = new IndexModel;
                    return $indexModel->responseVideo($postObj);
                } else if ($postObj->EventKey == 'V1001_LIKE') {

                    $content = "感谢您的点赞，我们会更加努力的";
                    $indexModel = new IndexModel;
                    return $indexModel->responseText($postObj, $content);
                }
            }
        }


        //回复消息
        if (strtolower($postObj->MsgType) == 'text') {
            if ($postObj->Content == 'imooc') {
                $content = "imooc这个讲师是个傻逼";

                $indexModel = new IndexModel;
                return $indexModel->responseText($postObj, $content);
            }
            if ($postObj->Content == '主页') {
                $content = "<a href='http://123.207.140.186:8080'>首页地址</a>";

                $indexModel = new IndexModel;
                return $indexModel->responseText($postObj, $content);
            }
            if ($postObj->Content == 'id') {
                $content = $postObj->FromUserName;

                $indexModel = new IndexModel;
                return $indexModel->responseText($postObj, $content);
            }

            if ($postObj->Content == 'news') {
                //指定返回值
                $arr = array(
                    array('title' => 'wantwin',
                        'describe' => '朱正仁的学习网站1',
                        'picUrl' => 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=3965705221,2010595691&fm=27&gp=0.jpg',
                        'url' => 'http://www.baidu.com'),
                    array('title' => 'wantwin',
                        'describe' => '朱正仁的学习网站2',
                        'picUrl' => 'http://123.207.140.186:8080/uploads/20180928/38892e55b380e3c0cf28e9707bdc1d1e.jpeg',
                        'url' => 'http://123.207.140.186'),
                    array('title' => 'wantwin',
                        'describe' => '朱正仁的学习网站1',
                        'picUrl' => 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=3965705221,2010595691&fm=27&gp=0.jpg',
                        'url' => 'http://www.baidu.com'),
                );

                $indexModel = new IndexModel;
                return $indexModel->responseNews($postObj);
            }

            if (strpos($postObj->Content, '天气') !== false) {
                $city = str_replace("天气", "", $postObj->Content);
                $indexModel = new IndexModel;
                return $indexModel->responseWeather($postObj, $city);
            }
        }
    }

    public function http_curl($url, $type = "get", $res = 'json', $arr = "") {
        //1.初始化curl
        $ch = curl_init();
        //2。设置curl参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        //3。采集
        $output = curl_exec($ch);

        if ($res == 'json') {
            if (curl_errno($ch)) {
                return curl_errno($ch);
            } else {
                return json_encode($output, true);
            }
        }
        //4。关闭
        curl_close($ch);
    }

    //获取token，保存在session 测试微信号使用
    public function getWxAccessToken() {

        if (Session::has('access_token') && Session::get('expires_in') > time()) {
            return Session::get('access_token');
        } else {
            $APPID = 'wx02100be493fa52b0';
            $APPSECRET = '64edddb78a895054ae63fc98bd2fafe7';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $APPID . "&secret=" . $APPSECRET;
            $res = json_decode($this->http_curl($url, 'get', 'json'), true);
            $res = json_decode($res, true);

            $access_token = $res['access_token'];
            Session::set("access_token", $res['access_token']);
            Session::set('expires_in', $res['expires_in'] + time());
            return $access_token;
        }
    }

    //创建菜单
    public function definedItem() {
        header("content-type:text/html;charset=utf-8");
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->getWxAccessToken();
        $postArr = array(
            'button' => array(
                array(
                    "type" => "click",
                    "name" => urlencode("今日歌曲"),
                    "key" => "V1001_TODAY_MUSIC"
                ),
                array(
                    "name" => urlencode("菜单"),
                    "sub_button" => array(
                        array(
                            "type" => "view",
                            "name" => urlencode("搜索"),
                            "url" => "http://www.soso.com/"
                        ),
                        array(
                            "type" => "click",
                            "name" => urlencode("赞一下我们"),
                            "key" => "V1001_LIKE"
                        )
                    )
                ))
        );

        $postJson = urldecode(json_encode($postArr, true));
        $res = $this->http_curl($url, 'post', 'json', $postJson);
        dump($res);
    }

    //群发消息
    public function msgAll() {
        header("content-type:text/html;charset=utf-8");

        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=" . $this->getWxAccessToken();
        // $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?" . $this->getWxAccessToken();
        $postArr = array(
            'touser' => "oLxOC0UGbVXq2fvayW5AkFKuCJXU",
            "text" => array(
                "content" => "朱正仁，你可以的，我爱王琰"
            ),
            "msgtype" => "text"
        );

        $postJson = json_encode($postArr, JSON_UNESCAPED_UNICODE);

        $res = $this->http_curl($url, 'post', 'json', $postJson);
        dump($res);
    }

    //发送模板信息
    public function sendTemplateMessage() {
        $templateId = "41cz6wCIG7TPU-Nvv6dMwYwzwok-18GdDriQrny1Te8";
        header("content-type:text/html;charset=utf-8");

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->getWxAccessToken();
        $postArr = array(
            'touser' => "oLxOC0UGbVXq2fvayW5AkFKuCJXU",
            "template_id" => $templateId,
            "url" => "http://www.soso.com/",
            "data" => array(
                "name" => array(
                    "value" => "朱正仁",
                    "color" => "#173177"
                ),
                "money" => array(
                    "value" => "100",
                    "color" => "#173177"
                ),
                "date" => array(
                    "value" => date('Y-m-d H:i:s'),
                    "color" => "#ff0000"
                )
            )
        );

        $postJson = json_encode($postArr, JSON_UNESCAPED_UNICODE);

        $res = $this->http_curl($url, 'post', 'json', $postJson);
        dump($res);
    }

    //获取用户的opened
    public function getBaseInfo() {
        /*
         * 1 第一步：用户同意授权，获取code
          2 第二步：通过code换取网页授权access_token
          3 第三步：刷新access_token（如果需要）
          4 第四步：拉取用户信息(需scope为 snsapi_userinfo)
          5 附：检验授权凭证（access_token）是否有效
         * 
         */
        $APPID = 'wx02100be493fa52b0';
        $APPSECRET = '64edddb78a895054ae63fc98bd2fafe7';
        $redirect_uri = urlencode("https://www.wantwin.xyz/wx/index/getUserOpenId");
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="
                .$APPID."&redirect_uri="."$redirect_uri"
                ."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
        header('location:'.$url);
    }
    public function getUserOpenId(){
        $APPID = 'wx02100be493fa52b0';
        $APPSECRET = '64edddb78a895054ae63fc98bd2fafe7';
        $code=$_GET['code'];
        
        if(Session::has('ac_token')){
            $ac_token = Session::get('ac_token');
            Session::delete('ac_token');
            dump($ac_token);
            return 0;
        }
        
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$APPID."&secret=".$APPSECRET."&code=".$code."&grant_type=authorization_code";
        $res = json_encode($this->http_curl($url,'get'),true);
        dump($res);
     //   Session::set('ac_token',$res['access_token']);
        
    }

}
