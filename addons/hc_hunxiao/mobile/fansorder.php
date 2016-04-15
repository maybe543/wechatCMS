<?php
	$from_user = $_W['openid'];
	
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if(empty($profile)){
		message('请先注册',$this->createMobileUrl('register'),'error');
		exit;
	}
	if($op=='display'){
		$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		if(empty($userdefault)){
			$userdefault = 1;
		}
		$list = array();
		for($i=1; $i<=$userdefault; $i++){
			$list[$i] = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$i." and weid = ".$weid." ORDER BY createtime DESC LIMIT 11");
			if(empty($list[$i])){
				unset($list[$i]);
				break;
			}
		}
	}
	
	if($op=='more'){
		$level = intval($_GPC['level']);
		$list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_memberrelative') . " WHERE shareid = ".$id." and userdefault = ".$level." and weid = ".$weid." ORDER BY createtime DESC");
	}
	
	$goods = pdo_fetchall("select id, title from ".tablename('hc_hunxiao_goods'). " where weid = ".$weid. " and status = 1");
	$orders = pdo_fetchall("select id, status from ".tablename('hc_hunxiao_order'). " where weid = ".$weid);
	$good = array();
	$order = array();
	foreach($goods as $g){
		$good[$g['id']] = $g['title'];
	}
	foreach($orders as $g){
		$order[$g['id']] = $g['status'];
	}
	include $this->template('fansorder');
?>