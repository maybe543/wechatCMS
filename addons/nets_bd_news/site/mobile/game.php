<?php
global $_GPC, $_W;

//BEGIN 验证是否登录，并取出uid
if(empty($_W["member"]["uid"]) && empty($_GPC["hxs_uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
//会员信息
$uid=$_W["member"]["uid"];
if(!empty($_GPC["hxs_uid"])){
	$uid=$_GPC["hxs_uid"];
}
//END
if(empty($uid)){
	$uid=0;
}
$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);
//活动信息 top 3 出来
$games=pdo_fetchall("select * from ims_netsbd_integral_game_set where uniacid=".$_W['uniacid']."  ORDER BY  ishome DESC,createtime DESC LIMIT 0,15");
//AND endtime>now()
include $this->template('game');
?>