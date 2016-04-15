<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;



$foo = empty($_GPC['foo']) ? "listPt" : $_GPC['foo'];
$op = empty($_GPC['op']) ? "listPt" : $_GPC['op'];
$type = empty($_GPC['type']) ? '1' : $_GPC['type'];

if(empty($_GPC['id']))
	$hbid = $_GPC['hbid'];
else
	$hbid = pdo_fetchcolumn('select id from '.tablename('ice_yzmhb').' where rid =:rid',array(':rid' =>$_GPC['id']));

if($op == "listPt"){
	
	$res = listPt($hbid,$type);
	$list = $res['list'];
	$pager = $res['pager'];
	
	
	include $this->template("listPt");
	
	
}else if($op == "show"){
	
	
	
	
	
}


function listPt($hbid,$type){
	
	load()->func("logging");
	
	global  $_W,$_GPC;
	$content = "";
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 15;
	$param = array();
	$param[':uniacid'] = $_W['uniacid'];
	
	$content = ' ';
	
	
	$content .= " and yzmhbid = ".$hbid;
	$content .= " and type = ".$type;
	
	
	$listSql = "select * from ".tablename("ice_yzmhb_prize")."  where uniacid = :uniacid   ".$content ." order by time desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize ;
	
	logging_run($listSql,'','listSql');
	$list = pdo_fetchall($listSql,$param);
	$sql = "select count(*) from ".tablename("ice_yzmhb_prize")."  where uniacid = :uniacid  ".$content ;
	$total = pdo_fetchcolumn($sql,$param);
	$pager = pagination($total, $pindex, $psize);
	
	// 	$list = pdo_fetchall("select * from ".tablename("ice_gtja_baoming")." where uniacid = :uniacid and aid = :aid",array(":uniacid"=>$_W['uniacid'],":aid"=>$aid));
	
	$result = array();
	$result['list'] = $list;
	$result['pager'] = $pager;
	return $result;
	
	
	
	
	
	}







	
	


