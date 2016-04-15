<?php
global $_GPC, $_W;
$cid=-2;
if(!empty($_GPC["cid"])){
	$cid=$_GPC["cid"];
}
$condition = '  cid = :cid AND uniacid=:uniacid';
$pars=array();
$pars[':uniacid']=$_W['uniacid'];
$pars[':cid']=$cid;
$help=pdo_fetchall("select * from ".tablename('netsbd_news')." where cid<0 AND cid = :cid AND uniacid=:uniacid ORDER BY sort DESC, ID DESC",$pars);
include $this->template('help');
?>