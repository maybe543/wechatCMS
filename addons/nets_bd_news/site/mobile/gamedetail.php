<?php
global $_GPC, $_W;
//BEGIN 验证是否登录，并取出uid
if(empty($_W["member"]["uid"]) && empty($_GPC["hxs_uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
//会员信息
$uid=$_W["member"]["uid"];
if(!empty($_GPC["hxs_uid"])){
	$uid=$_GPC["hxs_uid"];
}
//END
if(empty($uid)){
	$uid=0;
}
$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);
//活动信息 top 3 出来
$game=pdo_fetch("select * from ims_netsbd_integral_game_set where id=:id AND uniacid=".$_W['uniacid']." ORDER BY createtime DESC LIMIT 0,3",array(":id"=>$_GPC["id"]));

$game_record_sql="SELECT r.uid,r.gameid,s.title,s.picture,s.prize,s.endtime,r.state,r.createtime,s.num_eq_result FROM ims_netsbd_integral_game_record AS r LEFT JOIN ims_netsbd_integral_game_set AS s ON s.id=r.gameid
WHERE r.uid=:uid AND r.uniacid=:uniacid";
$game_record=pdo_fetchall($game_record_sql,array(":uid"=>$uid,":uniacid"=>$_W['uniacid']));
include $this->template('gamedetail');
?>