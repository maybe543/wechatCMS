<?php
global $_GPC, $_W;
$pindex = max(1, intval($_GPC['page']));


$psize = 10;
$start = ($pindex - 1) * $psize;
$cid=0;
/*
if($cid==0){
	$pindex=$pindex+1;
}
*/
//新闻查询条件
$condition = '  cid>0 AND uniacid=:uniacid';
$pars=array();
$pars['uniacid']=$_W['uniacid'];
if(!empty($_GPC["cid"]) && $_GPC['cid']!="0"){
	$cid=$_GPC["cid"];
	$condition .=" AND `cid` = :cid";
	$pars[':cid'] = $cid;
}
$sql="select * from ".tablename('netsbd_news')." where ".$condition." ORDER BY  ishome DESC,ID DESC LIMIT {$start}, {$psize} ";
//print($sql);

$news=pdo_fetchall($sql,$pars);

//新闻总条数
$total_sql="select count(*) from ".tablename('netsbd_news')." where ".$condition;
$total = pdo_fetchcolumn($total_sql,$pars);
//计算分页数
$total_page=1;
if($total%$psize==0){
	$total_page=intval($total/$psize);
}else{
	$total_page=intval(($total/$psize))+1;
}
include $this->template('ajax_newslist');
?>