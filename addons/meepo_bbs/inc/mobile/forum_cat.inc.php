<?php
global $_W,$_GPC;
$tempalte = $this->module['config']['name']?$this->module['config']['name']:'default';

$catid = $_GPC['catid'];

$list = getCat();

if(empty($_W['member']['uid'])){
	$user['groupid'] = -1;
}else{
	$user = mc_fetch($_W['member']['uid'],array('groupid'));
}

foreach ($list as $li){
	$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
	$params = array(':fid'=>$li['typeid']);
	$li['total_num'] = pdo_fetchcolumn($sql,$params);
	$group = unserialize($li['look_group']);
	if(in_array($user['groupid'], (array)$group)){
		$cats[] = $li;
	}
}


if(empty($catid)){
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND isgood = :isgood";
	$params = array(':uniacid'=>$_W['uniacid'],':isgood'=>1);
	$list = pdo_fetchall($sql,$params);
	
	foreach ($list as $li){
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
		$params = array(':fid'=>$li['typeid']);
		$li['total_num'] = pdo_fetchcolumn($sql,$params);
		$group = unserialize($li['look_group']);
		if(in_array($user['groupid'], (array)$group)){
			$good_cats[] = $li;
		}
	}
}else{
	$params = array(':uniacid'=>$_W['uniacid'],':fid'=>$catid);
	$sql = "SELECT * FROM ".tablename('meepo_bbs_threadclass')." WHERE uniacid = :uniacid AND fid = :fid ORDER BY displayorder DESC";
	$list = pdo_fetchall($sql,$params);
	
	foreach ($list as $li){
		$sql = "SELECT COUNT(*) FROM ".tablename('meepo_bbs_topics')." WHERE fid = :fid ";
		$params = array(':fid'=>$li['typeid']);
		$li['total_num'] = pdo_fetchcolumn($sql,$params);
		$group = unserialize($li['look_group']);
		if(in_array($user['groupid'], (array)$group)){
			$good_cats[] = $li;
		}
	}
}

include $this->template($tempalte.'/templates/forum/cat');