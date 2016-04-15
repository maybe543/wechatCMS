<?php
global $_W,$_GPC;
if(!empty($this->module['config']['appid'])&&!empty($this->module['config']['appsecret'])) {
	$this->auth();
}else{
	$user_agent  = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') === false) {
		die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
	}
}
$openid=$_W['openid'];
$uniacid=$_W['uniacid'];
$pid=$_GPC['pid'];
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");

$res=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_recuit_basic')." where uniacid = '{$_W['uniacid']}' and openid='".$openid."'");
if($res>0){
	//已经填写过基本信息了,去填写简历,直接投递简历
	$op=1;
	//简历完整度计算 17个信息
	//查询简历信息
	$mylist=pdo_fetch("select uname,sex,age,ed,mobile,email,avatar,present,birth,height,weight,register,address,marriage,school from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_info')." as b on a.openid=b.openid
				where a.openid='".$openid."' and a.uniacid=".$uniacid."");
		
	//循环遍历出工作经验
	$myexpers=pdo_fetchall("select * from ".tablename('enjoy_recuit_exper')." where openid='".$openid."' and uniacid=".$uniacid."");
	$mylist['exper']=$myexpers;
	//循环遍历出证书
	$mycard=pdo_fetchall("select * from ".tablename('enjoy_recuit_card')." where openid='".$openid."' and uniacid=".$uniacid."");
	$mylist['card']=$mycard;
	$count=0;
	foreach ($mylist as $v){
		if($v!=null){
			$count++;
		}
	}
	$chance=Intval($count/17*100);
	//更新时间
	$update=pdo_fetchcolumn("select createtime from ".tablename('enjoy_recuit_basic')." where openid='".$openid."' and uniacid=".$uniacid."");
		
		
}else{
	//填写基本信息
	$op=0;
}

include $this->template('vote');