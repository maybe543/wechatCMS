<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Money.class.php';
require_once HK_ROOT . '/module/Father.class.php';
require_once HK_ROOT . '/module/Record.class.php';
require_once HK_ROOT . '/module/Fan.class.php';
require_once HK_ROOT . '/function/common.func.php';
$from = $_GPC['fm'];
if(empty($from)){
    $this->error('访问错误');
}
load()->model('mc');
//一旦点击收入查看则必须授权获取用户基本信息
$userinfo = mc_oauth_userinfo();
$fan = new Fan();
if(!empty($_W['fans']['from_user'])){
    $exists = $fan->getOne($_W['fans']['from_user']);
    if(!$exists){
        $input = array();
        $input['openid'] = $_W['fans']['from_user'];
        $input['nickname'] = $userinfo['nickname'];
        $input['avatar'] = $userinfo['avatar'];
        $fan->create($input);
    }
}
//将关系写入记录
$result = father_handle($from);
if($result){
    message('您已正式成为家族成员',$this->createMobileUrl('list'),'success');
}else{
    message('您已经是其他家族成员,不能重复加入',$this->createMobileUrl('list'),'info');
}