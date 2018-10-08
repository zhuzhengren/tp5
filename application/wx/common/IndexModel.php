<?php

namespace app\wx\common;


class IndexModel {

    public function responseNews($postObj, $arr) {

//整理模版
        $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <ArticleCount>" . count($arr) . "</ArticleCount>
                            <Articles>
                            ";
        foreach ($arr as $k => $v) {
            $template .= "<item>
                    <Title><![CDATA[" . $v['title'] . " ]]></Title>
                    <Description><![CDATA[" . $v['describe'] . "]]></Description>
                    <PicUrl><![CDATA[" . $v['picUrl'] . "]]></PicUrl>
                    <Url><![CDATA[" . $v['url'] . "]]>
                    </Url>
                    </item>
                    ";
        }
        $template .= "</Articles></xml  >";
        $template = str_replace(" ", '', $template);
//构造返回内容，并且输出
        $fromUser = $postObj->ToUserName;
        $toUser = $postObj->FromUserName;
        $time = time();
        $msgType = 'news';
        $info = sprintf($template, $toUser, $fromUser, $time, $msgType);
        return $info;
    }

    public function responseText($postObj, $content) {
        $template = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";

        $fromUser = $postObj->ToUserName;
        $toUser = $postObj->FromUserName;
        $time = time();
        $msgType = 'text';
        $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
        return $info;
    }
    
    public function responseWeather($postObj, $city){
        $template = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";

        $fromUser = $postObj->ToUserName;
        $toUser = $postObj->FromUserName;
        $time = time();
        $msgType = 'text';
        
        $weather = new Weather;
        $content = $weather->getWeather($city);
        
        $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
        return $info;
    }

    public function responseVideo($postObj){
        /**
         * 回复视频使用的模板
         */
        $template = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Video>
            <MediaId><![CDATA[%s]]></MediaId>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            </Video> 
            </xml>";
        $fromUser = $postObj->ToUserName;
        $toUser = $postObj->FromUserName;
        $time = time();
        $msgType = 'video';
        $mediaId = "r-2wE4iqHAHpMowIx1i9I2XqMMJd7X80xqENytZohXqYtek3NhPNEgxyPvRL4g_G";
        $title = "video_title";
        $description = "my video";
        
        $info = sprintf($template, $toUser, $fromUser, $time, $msgType, $mediaId, $title, $description);
        return $info;
    }

    
    public function getAccessToken() {
        $cu = curl_init();

        $APPID = WxConf::$APPID;
        $APPSECRET = WxConf::$APPSECRET;
        
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $APPID . "&secret=" . $APPSECRET;

        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($cu);
        if (curl_errno($cu)) {
            dump(curl_error($cu));
        }
        $token = json_decode($res, true);
        return $token['access_token'];
    }

    public function getWxServerIp() {
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=" . $this->getAccessToken();

        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);

        $res = curl_exec($cu);
        if (curl_errno($cu)) {
            dump(curl_error($cu));
        }
        return $res;
        $ipList = json_decode($res, true);
        return dump($ipList);
    }

}
