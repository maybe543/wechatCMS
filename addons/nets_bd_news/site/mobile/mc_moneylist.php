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
$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$start = ($pindex - 1) * $psize;

if(empty($uid)){
	$uid=0;
}
//分页取积分记录
$moneylist=pdo_fetchall("select * from ".tablename('mc_credits_record')." where uid=:uid ORDER BY ID DESC  LIMIT {$start}, {$psize} ",array(':uid'=>$uid));
//var_dump($moneylist);
//取出总数
$total_sql="select count(*) from ".tablename('mc_credits_record')." where uid=:uid";
$total = pdo_fetchcolumn($total_sql,array(':uid'=>$uid));
$total_page=1;
if($total%$psize==0){
	$total_page=intval($total/$psize);
}else{
	$total_page=intval(($total/$psize))+1;
}

// 1级贡献
$sql_1="SELECT SUM(num) FROM ims_mc_credits_record WHERE uid =:uid and remark='^一级会员贡献^';";
$level_1_i = pdo_fetchcolumn($sql_1,array(':uid'=>$uid));
// 2级贡献
$sql_2="SELECT SUM(num) FROM ims_mc_credits_record WHERE uid =:uid and remark='^二级会员贡献^';";
$level_2_i = pdo_fetchcolumn($sql_2,array(':uid'=>$uid));
// 3级贡献
$sql_3="SELECT SUM(num) FROM ims_mc_credits_record WHERE uid =:uid and remark='^三级会员贡献^';";
$level_2_i = pdo_fetchcolumn($sql_3,array(':uid'=>$uid));
include $this->template('mc_moneylist');
?>