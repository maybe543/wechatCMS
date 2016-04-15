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
$condition = ' uniacid=:uniacid';
$pars=array();
$pars[':uniacid']=$_W['uniacid'];
$sql="select * from ".tablename('netsbd_set')." where ".$condition;
$set=pdo_fetch($sql,$pars);

$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);

$cash=pdo_fetchall("select * from ims_netsbd_user_exchange_cash where uid=".$uid." ORDER BY id DESC");

include $this->template('mc_moneycash');
?>