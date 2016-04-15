<?php
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$openid=$_GPC['openid'];

//查询简历信息
// 		$item=pdo_fetch("select a.*,b.birth,b.register,b.address,b.marriage,b.weight,b.height,b.school from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_info')." as b on a.openid=b.openid
// 				where a.openid='".$openid."' and a.uniacid=".$uniacid."");
$item=pdo_fetch("select a.*,b.birth,b.register,b.address,b.marriage,b.weight,b.height,b.school,c.avatar as weavatar from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_info')." as b on a.openid=b.openid left join
				 ".tablename('enjoy_recuit_fans')." as c on a.openid=c.openid where a.openid='".$openid."' and a.uniacid=".$uniacid."");
// 		var_dump($item);
// 		exit();
//循环遍历出工作经验
$myexpers=pdo_fetchall("select * from ".tablename('enjoy_recuit_exper')." where openid='".$openid."' and uniacid=".$uniacid."");
$item['exper']=$myexpers;
//循环遍历出证书
$mycard=pdo_fetchall("select * from ".tablename('enjoy_recuit_card')." where openid='".$openid."' and uniacid=".$uniacid."");
$item['card']=$mycard;


include $this->template('rdetail');