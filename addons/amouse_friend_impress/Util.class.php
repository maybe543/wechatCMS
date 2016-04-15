<?php

class Util{

    public static function str_murl($url){
        global $_W;
        return $_W['siteroot'].'app'.str_replace('./', '/', $url);
    }

    public static function  checkmobile(){
        $user_agent=$_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, 'MicroMessenger') === false){
            echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
            exit();
        }
    }

    public static function  getClientCookieUserInfo($cookieKey){
        global $_GPC;
        $session=json_decode(base64_decode($_GPC[$cookieKey]), true);
        return $session;
    }

    public static function setClientCookieUserInfo($userInfo=array(), $cookieKey){
        if(!empty($userInfo) && !empty($userInfo['openid'])){
            $cookie=array();
            $cookie['openid']=$userInfo['openid'];
            $cookie['nickname']=$userInfo['nickname'];
            $cookie['headimgurl']=$userInfo['headimgurl'];
            $session=base64_encode(json_encode($cookie));
            isetcookie($cookieKey, $session, 24 * 3600 * 1);
        } else {
            message("获取用户信息错误");
        }
    }

}