<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Money.class.php';
require_once HK_ROOT . '/module/Father.class.php';
require_once HK_ROOT . '/module/Record.class.php';
require_once HK_ROOT . '/module/Fan.class.php';
require_once HK_ROOT . '/function/common.func.php';
load()->model('mc');
//一旦点击收入查看则必须授权获取用户基本信息
$userinfo = mc_oauth_userinfo();
//echo $userinfo['avatar'];
//print_r($userinfo);
$input = array();
$input['openid'] = $_W['fans']['from_user'];
$input['nickname'] = $userinfo['nickname'];
$input['avatar'] = $userinfo['avatar'];
$fan = new Fan();
$fan->create($input);
//会员统计信息
$meminfo = getdata($_W['fans']['from_user']);
load()->func('cache');
$key = 'surpermoney'.$_W['fans']['from_user'];
cache_write($key,$meminfo);
//更新会员信息
$update = array();
$update['credit'] = $meminfo['totalmoney'];
$update['memnums'] = $meminfo['totalcount'];
$fan->modify($_W['fans']['from_user'],$update);
//可提积分
$fan = new Fan();
$faninfo = $fan->getOne($_W['fans']['from_user']);
$allowmoney = $meminfo['totalmoney'] - $faninfo['used'];
if($allowmoney < 0){
    $allowmoney = 0;
}
include $this->template('user');