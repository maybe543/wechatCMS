<?php
global $_W,$_GPC;

$sql = "SELECT * FROM ".tablename('meepo_bbs_set')." WHERE uniacid = :uniacid";
$params = array(':uniacid'=>$_W['uniacid']);
$row = pdo_fetch($sql,$params);
$set = iunserializer($row['set']);

$setting = $set;

$group = pdo_fetchall('SELECT groupid,title FROM ' . tablename('mc_groups') . " WHERE uniacid = '{$_W['uniacid']}'");

if(checksubmit('submit')){
	$data = array();
	$data['uniacid'] = $_W['uniacid'];
	$data['createtime'] = time();
	if(!empty($_GPC['content'])){
		$_GPC['content'] = htmlspecialchars_decode($_GPC['content']);
	}
	foreach ($_GPC as $key=>$g){
		$set[$key] = $g;
	}
	
	$data['set'] = iserializer($set);
	if(empty($setting)){
		pdo_insert('meepo_bbs_set',$data);
	}else{
		pdo_update('meepo_bbs_set',$data,array('uniacid'=>$_W['uniacid']));
	}
	message('提交成功',referer(),'success');
}

load()->func('tpl');
include $this->template('set');
