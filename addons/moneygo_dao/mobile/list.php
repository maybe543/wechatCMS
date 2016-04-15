<?php

   
   	global $_W;
	$cate =  pdo_fetchall("SELECT * FROM ".tablename('moneygo_cate')." WHERE uniacid = '{$uniacid}'");
	$moshi = pdo_fetchall("SELECT * FROM ".tablename('moneygo_moshi')." WHERE uniacid = '{$uniacid}'");

	//分类
	$cateid = trim($_GPC['cid']);
	$moid = trim($_GPC['moid']);
	$con='';
	if(!empty($cateid)){
		
		$con .= 'and cid ='.$cateid;
	}	
	if(!empty($moid)){
		
		$con .= ' and danjia ='.$moid;
	}
	
	
    
	$uniacid=$_W['uniacid'];
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('moneygo_goodslist')." WHERE uniacid = '{$uniacid}' and status =2 $con");
	

	$pindex = 1;
	$psize = 2;
	$condition = '';
	
	
	$s_pos = pdo_fetchall("SELECT * FROM ".tablename('moneygo_goodslist')." WHERE uniacid = '{$uniacid}' and status =2 $condition ORDER BY sid DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	include $this->template('list');
?>