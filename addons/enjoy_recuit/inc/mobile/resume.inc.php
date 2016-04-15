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
$jssdk = new JSSDK();
$signPackage = $jssdk->GetSignPackage();



//查询简历信息
$mylist=pdo_fetch("select * from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_info')." as b on a.openid=b.openid
				where a.openid='".$openid."' and a.uniacid=".$uniacid."");

if(empty($mylist['avatar'])){
	$mylist['avatar']=pdo_fetchcolumn("select avatar from ".tablename('enjoy_recuit_fans')." where openid='".$openid."' and uniacid=".$uniacid."");
}else{
	$mylist['avatar']=tomedia($mylist['avatar']);

}
// 		var_dump($mylist['avatar']);
// 		echo "select avatar from ".tablename('enjoy_recuit_fans')." where openid='".$openid."' and uniacid=".$uniacid."";
// 		var_dump($mylist);
// 		exit();
//循环遍历出工作经验
$myexpers=pdo_fetchall("select * from ".tablename('enjoy_recuit_exper')." where openid='".$openid."' and uniacid=".$uniacid."");
$mylist['exper']=$myexpers;
//循环遍历出证书
$mycard=pdo_fetchall("select * from ".tablename('enjoy_recuit_card')." where openid='".$openid."' and uniacid=".$uniacid."");
$mylist['card']=$mycard;




include $this->template('resume');