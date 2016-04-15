<?php
global $_GPC, $_W;
$cid=-2;
if(!empty($_GPC["cid"])){
	$cid=$_GPC["cid"];
}
$uid=$_GPC['hxs_uid'];
//$uid=27844;
if(!empty($_GPC['hxs_uid'])){
	$family1=pdo_fetchall("SELECT M.uid,M.nickname,M.credit2,M.avatar,M.createtime from ims_netsbd_mc_members_relation AS R LEFT JOIN ims_mc_members AS M ON M.uid=R.uid WHERE p_uid=".$uid);
}

include $this->template('myfamily');
?>