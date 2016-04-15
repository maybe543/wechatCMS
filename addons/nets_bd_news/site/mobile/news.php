<?php
global $_GPC, $_W;
/*
if(empty($_W["member"]["uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
*/
$pindex = max(1, intval($_GPC['page']));
$psize = 30;
$start = ($pindex - 1) * $psize;
//取出新闻总数
$total_sql="select count(*) from ".tablename('netsbd_news')." where cid>0 AND uniacid=:uniacid ORDER BY ishome DESC,ID DESC";
$total = pdo_fetchcolumn($total_sql,array(':uniacid'=>$_W['uniacid']));

$total_page=1;
if($total%$psize==0){
	$total_page=intval($total/$psize);
}else{
	$total_page=intval(($total/$psize))+1;
}
//新闻分类
$category=pdo_fetchall("select * from ".tablename('netsbd_news_category')." where uniacid=:uniacid AND ismenu=1 AND ishide=0 ORDER BY sort,ID",array(':uniacid'=>$_W['uniacid']));
//分页取新闻
$news=pdo_fetchall("select * from ".tablename('netsbd_news')." where cid>0 AND uniacid=:uniacid ORDER BY sort DESC, ishome DESC,ID DESC LIMIT {$start}, {$psize} ",array(':uniacid'=>$_W['uniacid']));
//var_dump($news);
include $this->template('news');
?>