<?php
global $_W,$_GPC;
load()->func('tpl');
if($_GPC['pid']==null){
	$sql = "SELECT * FROM ".tablename('ks_yhq')." ORDER BY `pid` DESC";		
	$clist = pdo_fetchall($sql);
	$sqlset = "SELECT * FROM ".tablename('ks_yhq_set');		
	$sets = pdo_fetch($sqlset);
	include $this->template('index');
}else if($_GPC['pid']==true){
	$pid = $_GPC['pid'];
	//echo $pid;
	$sql3 = 'SELECT * FROM '. tablename('ks_yhq') .' where `pid` = '.$pid;
	//echo $sql2;
	$covertss = pdo_fetch($sql3);
	$sql2 = 'SELECT * FROM '. tablename('ks_yhq_code') .' where `use` = 0 and `void` = 0 and `send` = 0 and `pid` = '.$pid;
	//echo $sql2;
	$account = pdo_fetch($sql2);
	if(count($account)<2){$account='本优惠码已经发放完毕，请过段时间再领取';}else{
		$usesql = 'update '.tablename('ks_yhq_code').' set `send`=1 WHERE ID = '.$account[id];
		pdo_run($usesql);
	}
	include $this->template('gets');
}else{
	
}

?>