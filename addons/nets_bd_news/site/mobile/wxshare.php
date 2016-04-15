<?php
global $_GPC, $_W;

//临时保存推荐人ID
if (!empty($_GPC['re'])) {
	$reid=intval($_GPC['re']);
	isetcookie('hxs_news_re', $reid, !empty($_GPC['reid']) ?  time()+3600 : 0);
	$temp=$_GPC['hxs_news_re'];
}
//如果是未登录状态，跳转去登录或者注册
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
//回来之后如果推荐人不为空，则建立上下级关系
if (!empty($_GPC['re'])) {
	$reid=intval($_GPC['re']);
	$uid=$_W["member"]["uid"];
	if(empty($uid)){
		$uid=0;
	}
	//先验证是否存在上下级关系
	$check_sql="SELECT * FROM ".tablename("netsbd_mc_members_relation")." WHERE uid=:uid AND p_uid=:puid";
	$record=pdo_fetch($check_sql,array(":uid"=>$uid,":puid"=>$reid));
	//不存在则添加
	if(empty($record)){
		$netsbd_mc_members_relation["uid"]=$uid;
		$netsbd_mc_members_relation["p_uid"]=$reid;
		$netsbd_mc_members_relation["createtime"]=TIMESTAMP;
		$i=pdo_insert("netsbd_mc_members_relation",$netsbd_mc_members_relation);
		//如果建立关系成功,计算积分
		if($i>0){
			//查询条件
			$condition = ' uniacid=:uniacid';
			$pars=array();
			$pars[':uniacid']=$_W['uniacid'];
			$sql="select * from ".tablename('netsbd_set')." where ".$condition;
			$set=pdo_fetch($sql,$pars);
			$integral=$set["reregster_eq_integral"];
			$type="re_register";
			$tag="^推荐好友注册_".$sourceid."^";
			$remark1="推荐好友注册得".$set['reregster_eq_integral']."积分";
			$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);
			$remark="^".$uid."^".$members['nickname']."^".$set['reregster_eq_integral']."^".$remark1."^".$type.$tag;
			$b=check_user_isget($uid,$_W['uniacid'],$tag);
			//只有为true时才会执行，说明用户没有从该新闻上获取过积分
			if($b){
				$result=count_user_credits($members,$set['reregster_eq_integral'],$remark,$re_uid,$_W['uniacid']);
			}
		}
		
	}
	//关系建立后，则移除cookie
	isetcookie('hxs_news_re', "", time()-3600);
}
function count_user_credits($members,$integral,$remark,$uid,$uniacid){
	//商户设置的积分
	$members["credit1"]=intval($members["credit1"])+intval($integral);
	//给会员加积分
	$i=pdo_update("mc_members",$members,array("uid"=>$uid));
	if($i>0){
		//积分增加成功增加日志记录
		$credits_record["uid"]=$uid;
		$credits_record["uniacid"]=$uniacid;
		$credits_record["credittype"]="credit1";
		$credits_record["num"]=$integral;
		$credits_record["operator"]=0;
		$credits_record["createtime"]=TIMESTAMP;
		$credits_record["remark"]=$remark;
		pdo_insert("mc_credits_record",$credits_record);
	}
	return $i;
}
//验证用户是否已获取过积分
function check_user_isget($uid,$uniacid,$tag){
	$sql="select * from ".tablename("mc_credits_record")." where uid=".$uid." AND uniacid=".$uniacid." AND remark LIKE '%".$tag."%'";
	$r=pdo_fetch($sql,$pars);
	if(empty($r)){
		return true;
	}else{
		return false;
	}
}
include $this->template('wxshare');
?>