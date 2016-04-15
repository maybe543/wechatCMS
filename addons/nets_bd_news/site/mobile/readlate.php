<?php
global $_GPC, $_W;
/*
if(empty($_W["member"]["uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
*/

if(!empty($_GPC['hxs_uid'])){
	
//分页取新闻
$news=pdo_fetchall("SELECT n.* FROM ims_netsbd_readlate AS l LEFT JOIN ims_netsbd_news AS n ON l.newid=n.id WHERE l.uid=".$_GPC['hxs_uid']." ORDER BY l.id DESC LIMIT 0,50");	
}



include $this->template('readlate');
?>