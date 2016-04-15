<?php
global $_GPC, $_W;
//BEGIN 验证是否登录，并取出uid

if(empty($_W["member"]["uid"]) && empty($_GPC["hxs_uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
//	checkauth();
}


//会员信息
$uid=$_W["member"]["uid"];
if(!empty($_GPC["hxs_uid"])){
	$uid=$_GPC["hxs_uid"];
}
//END
//print("<br/><br/>UID:::".$uid);
$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);
//分成信息
//今天0点
$begin=strtotime(date('Y-m-d', time()));
//大于今天0点的第一天数据
$income_today=0;//pdo_fetch("select * from ims_netsbd_incom where createtime > ".$begin." AND uniacid=".$_W['uniacid']." ORDER BY createtime DESC");
//小于今天0点的前一天数据
$income_yestday=0;//pdo_fetch("select * from ims_netsbd_incom where createtime < ".$begin." AND uniacid=".$_W['uniacid']." ORDER BY createtime DESC");
//活动信息
$games=pdo_fetchall("select * from ims_netsbd_integral_game_set where uniacid=".$_W['uniacid']." ORDER BY ishome DESC,createtime DESC ");
//今日收益
$credit1_sql="SELECT SUM(num) FROM ims_mc_credits_record  where num>0 AND uniacid=".$_W['uniacid']." AND uid=".$uid." AND credittype='credit2' AND createtime>".$begin;
//print($credit1_sql);
$mycredit1=pdo_fetchcolumn($credit1_sql);

//总收益
$credit2_sql="SELECT SUM(num) FROM ims_mc_credits_record  where uniacid=".$_W['uniacid']." AND uid=".$uid." AND credittype='credit2'";
//print($credit1_sql);
$mycredit2=pdo_fetchcolumn($credit2_sql);
include $this->template('money');
?>