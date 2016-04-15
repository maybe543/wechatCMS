<?php
  
     require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
   global $_W, $_GPC;
   $settings=$this->module['config'];  
   define('APPID', $settings['appid']);
   define('SECRET', $settings['secret']);
  $weid = $_W['uniacid'];
  if ($_GPC['code']=="authdeny" || empty($_GPC['code']))
  {
    exit("授权失败");
  }
  
  load()->func('communication');
  
  $appInfo =getAppInfo();
  $appid=$appInfo['appid'];
  $secret=$appInfo['appsecret'];
  $state = $_GPC['state'];
  $code = $_GPC['code'];
  $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
  $content = ihttp_get($oauth2_code);
  $token = @json_decode($content['content'], true);
  if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) 
  {
    echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'].'<h1>';
    exit;
  }
  $from_user = $token['openid'];
  setcookie("fromUser_$weid",$from_user, time()+3600*(24*5)); 
  $url=$_COOKIE["xoauthURL"];   
  header("location:$url");
  exit();