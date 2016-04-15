<?php

   
   	global $_W;
	$xinxi = pdo_fetch("SELECT * FROM".tablename('moneygo_wechat')."WHERE uniacid='{$_W['uniacid']}'");

	$uniacid=$_W['uniacid'];
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('moneygo_goodslist')." WHERE uniacid = '{$uniacid}' and status =2 ");
	$pindex = 1;
	$psize = 2;
	$condition = '';
	
	
	$s_pos = pdo_fetchall("SELECT * FROM ".tablename('moneygo_goodslist')." WHERE uniacid = '{$uniacid}' and status =2 $condition ORDER BY sid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	include $this->template('index');
?>