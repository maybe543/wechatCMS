<?php
/**
 * By 高贵血迹
 */
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];

// 判断是否需要授权
$check = false;

// 获取用户openid
$openid = $_W['openid'];

// 获取当期公众号设置
$sql = "SELECT * FROM ".tablename('uni_settings')." WHERE `uniacid`=:uniacid";
$unisetting  =  pdo_fetch($sql,array('uniacid'=>$_W['uniacid']));

// 获取粉丝公众号ID
if(!empty($unisetting['oauth'])) {
	$temp = unserialize($unisetting['oauth']);
	$weid = empty($temp['account']) ? $_W['uniacid'] : $temp['account'];
} else {
	$weid = $_W['uniacid'];
}

// 获取用户粉丝信息
$sql = "SELECT * FROM ".tablename('mc_mapping_fans')." WHERE `openid`=:openid AND `uniacid`=:uniacid AND `openid`<>''";
$fan = pdo_fetch($sql,array(":openid"=>$openid,":uniacid"=>$weid));

if($fan){
	// 获取会员信息
	$sql = "SELECT * FROM ".tablename('mc_members')." WHERE `uid`=:uid AND `uid`<>0 ";
	$member = pdo_fetch($sql,array(":uid"=>$fan['uid']));
	if($member['nickname']){
		$check = false;
		$_SESSION['authurl'] = "";
	}else{
		$check = true;
		$_SESSION['authurl'] = $url;
	}
}else{
	$check = true;
	$_SESSION['authurl'] = $url;
}

// 跳转到授权页面
if($check){
	echo "<script>window.location.href = '".$_W['siteroot'].'app/'.substr($this->createMobileUrl('auth'),2)."';</script>";
	exit();
}
?>