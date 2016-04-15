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
$pindex = max(1, intval($_GPC['page']))+1;


$psize = 20;
$start = ($pindex - 1) * $psize;
if(empty($uid)){
	$uid=0;
}
//分页取积分记录
$moneylist=pdo_fetchall("select * from ".tablename('mc_credits_record')." where uid=:uid ORDER BY ID DESC LIMIT {$start}, {$psize}",array(':uid'=>$uid));
$total=
include $this->template('mc_moneylist_ajax');
?>