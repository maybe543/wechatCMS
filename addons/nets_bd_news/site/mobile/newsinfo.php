<?php
global $_GPC, $_W;
/*
if(empty($_W["member"]["uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
*/
$id = max(1, intval($_GPC['id']));
//分页取新闻
$news=pdo_fetch("select * from ".tablename('netsbd_news')." where id=:id",array(':id'=>$id));

isetcookie("history_newid",$news['id'],0);
isetcookie("history_newcid",$news['cid'],0);

$hotnews=pdo_fetchall("SELECT n2.* FROM ims_netsbd_news AS n1 LEFT JOIN ims_netsbd_news AS n2 ON n1.cid=n2.cid WHERE n1.id=:id AND n2.id!=:id ORDER BY
 n2.click_num DESC LIMIT 0,5",array(':id'=>$id));
 
 
 //热门评论
 $commentnews=pdo_fetchall("SELECT c.*,m.nickname,m.avatar FROM ims_netsbd_news_comment AS c LEFT JOIN ims_mc_members AS m
ON m.uid=c.uid WHERE newsid=:id AND type=2 AND ishide=0 order by like_num DESC LIMIT 0,5",array(':id'=>$id));

 //最新评论
 $newcomment=pdo_fetchall("SELECT c.*,m.nickname,m.avatar FROM ims_netsbd_news_comment AS c LEFT JOIN ims_mc_members AS m
ON m.uid=c.uid WHERE newsid=:id AND type=2 AND ishide=0 order by id DESC LIMIT 0,20",array(':id'=>$id));
include $this->template('newsinfo');
?>