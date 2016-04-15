<?php
global $_GPC, $_W;
$result="-1";
//存在分享人的计算 BEGIN
$type=$_GPC["type"];
//查询条件
$condition = ' uniacid=:uniacid';
$pars=array();
$pars['uniacid']=$_W['uniacid'];
$sql="select * from ".tablename('netsbd_set')." where ".$condition;
$set=pdo_fetch($sql,$pars);
//^uid^username^^备注^类型^标签^
$sourceid=$_GPC["source"];
//var_dump($set);
//会员信息
$uid=$_GPC["hxs_uid"];



if($type=="click"){
	$re_uid=$_GPC['re'];
	if(!empty($re_uid) && $uid!=$re_uid){
		$members_re=pdo_fetch("select * from ims_mc_members where uid=".$re_uid);
		//这里分享被读以新闻ID，粉丝openidId为唯一标签
		$tag="^新闻分享被阅读_".$sourceid."_".$_W['fans']['from_user']."^";
		$type="share";
		$remark1="分享被读_".$set['beclick_eq_integral']."";
		$remark="^".$re_uid."^".$members_re['nickname']."^".$set['beclick_eq_integral']."^".$remark1."^".$type.$tag;
		//验证同一标签是否获取过积分
		$check_is_get_sql="SELECT count(0) FROM ims_mc_credits_record WHERE remark LIKE '%".$tag."%'";
		//print($check_is_get_sql);
		$count=pdo_fetchcolumn($check_is_get_sql);
		//不存在记录则计算相关获得积分
		
		if(intval($count)==0){
			$result=count_user_credits($members_re,$set['beclick_eq_integral'],$remark,$re_uid,$_W['uniacid']);
		}
		//添加新闻浏览记录  type=4 是分享被浏览
		insert_news_comment($newsid,4,$re_uid,$_W['uniacid'],$tag);
		
		$fans_sql="SELECT * FROM ".tablename('mc_mapping_fans') . " WHERE  `uid` = '{$re_uid}'";
		
		$f_log = pdo_fetch($fans_sql);
		if(!empty($f_log)){
			//发送模版消息
			$openid=$f_log["openid"];
			if(!empty($openid)){
				$data=get_clickdata($_W['account']['name']);
				if(!empty($set['template_msg3'])){
					$template_id=$set['template_msg3'];
					sendTemplateMsg($openid,$data,$template_id,$url="");
				}
			}
		}
		
		//回来之后如果推荐人不为空，则建立上下级关系 BEGIN
		$reid=$re_uid;
		$uid=$_W["member"]["uid"];
		if(empty($uid)){
			$uid=$_GPC["hxs_uid"];
		}
		if($uid>0 && $uid!=$reid){
			//先验证是否存在上下级关系
			$check_sql="SELECT * FROM ".tablename("netsbd_mc_members_relation")." WHERE uid=:uid";
			$record=pdo_fetch($check_sql,array(":uid"=>$uid));
			//不存在则添加
			if(empty($record)){
				$netsbd_mc_members_relation["uid"]=$uid;
				$netsbd_mc_members_relation["p_uid"]=$reid;
				$netsbd_mc_members_relation["createtime"]=TIMESTAMP;
				$i=pdo_insert("netsbd_mc_members_relation",$netsbd_mc_members_relation);
				//如果建立关系成功,计算
				if($i>0){
					//查询条件
					$condition = ' uniacid=:uniacid';
					$pars=array();
					$pars['uniacid']=$_W['uniacid'];
					$sql="select * from ".tablename('netsbd_set')." where ".$condition;
					$set=pdo_fetch($sql,$pars);
					$integral=$set["reregster_eq_integral"];
					$type="re_register";
					$tag="^推荐好友注册_".$reid."^";
					$remark1="推荐好友注册得".$set['reregster_eq_integral']."";
					$members=pdo_fetch("select * from ims_mc_members where uid=".$reid);
					$remark="^".$uid."^".$members['nickname']."^".$set['reregster_eq_integral']."^".$remark1."^".$type.$tag;
					$result=count_user_credits($members,$set['reregster_eq_integral'],$remark,$reid,$_W['uniacid']);
					
					if(!empty($set['template_msg4'])){
						$template_id=$set['template_msg4'];
						$data=get_newmemberdata($members_re['nickname']);
						sendTemplateMsg($openid,$data,$template_id,$url="");
					}
				}
			}
			//关系建立后，则移除cookie
			isetcookie('hxs_news_re', "", time()-3600);
		}
		// 建立关系 END
	}
}
//存在分享人的计算 END

//分享得积分BEGIN
if($type=="share_wx"){
	if(!empty($uid) && $uid!=0){
		$members_re=pdo_fetch("select * from ims_mc_members where uid=".$uid);
		//这里分享被读以新闻ID，粉丝openidId为唯一标签
		$tag="^分享新闻_".$sourceid."_".$_W['fans']['from_user']."^";
		$type="share";
		$remark1="分享新闻_".$set['share_eq_integral']."";
		$remark="^".$uid."^".$members_re['nickname']."^".$set['share_eq_integral']."^".$remark1."^".$type.$tag;
		//验证同一标签是否获取过积分
		$check_is_get_sql="SELECT count(0) FROM ims_mc_credits_record WHERE remark LIKE '%".$tag."%'";
		//print($check_is_get_sql);
		$count=pdo_fetchcolumn($check_is_get_sql);
		//不存在记录则计算相关获得积分
		
		if(intval($count)==0){
			//print("<br/>1235");
			$result=count_user_credits($members_re,$set['share_eq_integral'],$remark,$uid,$_W['uniacid']);
			//print("<br/>1236");
		}
	}
}
// 分享得积分END
//请先登录后在操作
if(empty($_GPC["hxs_uid"])){
	$result="-101";
	include $this->template('ajax_common');
	exit;
}

if(empty($uid)){
	$uid=0;
}
$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);

//新闻稍后阅读
if($type=="readlate"){
	$late['uid']=$uid;
	$late['newid']=$sourceid;
	
	$late['createtime']=TIMESTAMP;
	
	$check_record=pdo_fetch("select * from ims_netsbd_readlate where uid=:uid AND newid=:newid",array(":uid"=>$uid,":newid"=>$sourceid));
	if(empty($check_record)){
		$result=pdo_insert("netsbd_readlate",$late);
	}else{
		$result="0";
	}
	include $this->template('ajax_common');
	exit;
}
//删除新闻稍后阅读
if($type=="readlate_del"){
	
	$check_record=pdo_fetch("select * from ims_netsbd_readlate where uid=:uid AND newid=:newid",array(":uid"=>$uid,":newid"=>$sourceid));
	if(!empty($check_record)){
		$late['id']=$check_record['id'];
		$result=pdo_delete("netsbd_readlate",$late);
	}else{
		$result="0";
	}
	include $this->template('ajax_common');
	exit;
}

//登录相关 BEGIN
if($type=="login"){
	$remark1="每日登录得".$set['login_eq_integral']."";
	//组合备注说明
	$tag="^每日登录_".$sourceid."^";
	$remark="^".$uid."^".$members['nickname']."^".$set['login_eq_integral']."^".$remark1."^".$type.$tag;
	
	//验证是否要增加 BEGIN
	$b=check_user_isget($uid,$_W['uniacid'],$tag);
	//只有为true时才会执行，说明用户没有从该新闻上获取过
	if($b){
		$result=count_user_credits($members,$set['login_eq_integral'],$remark,$uid,$_W['uniacid']);
	}
	//回来之后如果推荐人不为空，则建立上下级关系
	if (!empty($_GPC['re'])) {
		$reid=intval($_GPC['re']);
		$uid=$_W["member"]["uid"];
		//先验证是否存在上下级关系
		$check_sql="SELECT * FROM ".tablename("netsbd_mc_members_relation")." WHERE uid=:uid AND p_uid=:puid";
		$record=pdo_fetch($check_sql,array(":uid"=>$uid,":puid"=>$reid));
		//不存在则添加
		if(empty($record)){
			$netsbd_mc_members_relation["uid"]=$uid;
			$netsbd_mc_members_relation["p_uid"]=$reid;
			$netsbd_mc_members_relation["createtime"]=TIMESTAMP;
			$i=pdo_insert("netsbd_mc_members_relation",$netsbd_mc_members_relation);
			//如果建立关系成功,计算
			if($i>0){
				//查询条件
				$condition = ' uniacid=:uniacid';
				$pars=array();
				$pars['uniacid']=$_W['uniacid'];
				$sql="select * from ".tablename('netsbd_set')." where ".$condition;
				$set=pdo_fetch($sql,$pars);
				$integral=$set["reregster_eq_integral"];
				$type="re_register";
				$tag="^推荐好友注册_".$sourceid."^";
				$remark1="推荐好友注册得".$set['reregster_eq_integral']."";
				$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);
				$remark="^".$uid."^".$members['nickname']."^".$set['reregster_eq_integral']."^".$remark1."^".$type.$tag;
				$b=check_user_isget($uid,$_W['uniacid'],$tag);
				//只有为true时才会执行，说明用户没有从该新闻上获取过
				if($b){
					$result=count_user_credits($members,$set['reregster_eq_integral'],$remark,$re_uid,$_W['uniacid']);
				}
			}
		}
		//关系建立后，则移除cookie
		isetcookie('hxs_news_re', "", time()-3600);
	}
	//存在分享人的计算 END
	//验证是否要增加 END
}
//登录相关 END
//BEGIN. 新闻相关
//会员提现
if($type=="money_cash"){
	$min_cashmoney=$set['min_cashmoney'];
	$mymoney=$_GPC["money"];
	if(floatval($mymoney)>floatval($members["credit2"])){
		print("-20001");//余额不足的
		exit;
	}
	if(floatval($mymoney)==0){
		print("-20002");//提现金额不能为0
		exit;
	}
	if(floatval($mymoney)<floatval($min_cashmoney)){
		print("-20003");//提现金额不能小于30
		exit;
	}
	$cash["uid"]=$uid;
	$cash["uniacid"]=$_W['uniacid'];
	$cash["cash"]=$mymoney;
	$cash["cash_type"]=1;//支付宝
	$cash["state"]=0;//提现审核中
	if(!empty($_W['fans']['from_user'])){
		$cash["cash_type"]=2;//微信
	}
	$cash["createtime"]=TIMESTAMP;
	$cash["finishtime"]=TIMESTAMP;
	$i=pdo_insert("netsbd_user_exchange_cash",$cash);
	if($i>0){
		$members["credit2"]=intval($members["credit2"])-intval($mymoney);
		$i=pdo_update("mc_members",$members,array("uid"=>$members["uid"]));
		$result=$i;
	}else{
		$result=0;//提现失败
	}
}
//会员点击文章阅读，获得 个人阅读暂时没有积分点数了
if($type=="click"){
	$remark1="阅读得".$set['click_eq_integral']."";
	//组合备注说明
	$tag="^新闻_".$sourceid."^";
	$remark="^".$uid."^".$members['nickname']."^".$set['click_eq_integral']."^".$remark1."^".$type.$tag;
	//新闻点击数加1 BEGIN
	$news=pdo_fetch("select * from ".tablename("netsbd_news")." where id=:id",array(":id"=>$sourceid));
	$news["click_num"]=$news["click_num"]+1;
	pdo_update("netsbd_news",$news,array("id"=>$sourceid));
	//新闻点击数加1 END
	//验证是否要增加 BEGIN
	/*
	$b=check_user_isget($uid,$_W['uniacid'],$tag);
	
	//只有为true时才会执行，说明用户没有从该新闻上获取过
	if($b){
		//print("<br/>1:".$b."<br/>");
		//var_dump($members);
		//$result=count_user_credits($members,$set['click_eq_integral'],$remark,$uid,$_W['uniacid']);
	}
	*/
	//验证是否要增加 END
}

//END.新闻相关

//BEGIN. 活动相关
if($type=="partin_game"){
	$i=check_partin_game($sourceid,$members,$uniacid);
	//$result=$i;
	
	if($i==1){
		$b=partin_game($sourceid,$uid,$_W['uniacid']);
		if($b){
			$result=1;//参与成功
		}else{
			$result=0;//您已参与
		}
	}else{
		$result=$i;
	}
}
//END. 活动相关
//计算
function count_user_credits($members,$integral,$remark,$uid,$uniacid){
	//商户设置的
	//print("<br/>1:".$uid."-".$integral."<br/>");
	$members["credit2"]=floatval($members["credit2"])+floatval($integral);
	//给会员加
	$i=pdo_update("mc_members",$members,array("uid"=>$uid));
	//print("<br/>1:".$uid."-".$i."<br/>");
	if($i>0){
		//增加成功增加日志记录
		$credits_record["uid"]=$uid;
		$credits_record["uniacid"]=$uniacid;
		$credits_record["credittype"]="credit2";
		$credits_record["num"]=$integral;
		$credits_record["operator"]=0;
		$credits_record["createtime"]=TIMESTAMP;
		$credits_record["remark"]=$remark;
		pdo_insert("mc_credits_record",$credits_record);
	}
	count_parent_credits($uid,$uniacid,$integral);
	return $i;
}

function count_parent_credits($uid,$uniacid,$integral){
	
	//print("<br/>uid:".$uid);
	global $_GPC, $_W;
	//查询条件
	$condition = ' uniacid=:uniacid';
	$pars=array();
	$pars['uniacid']=$_W['uniacid'];
	$sql="select * from ".tablename('netsbd_set')." where ".$condition;
	$set=pdo_fetch($sql,$pars);

	$sql="select p_uid from ims_netsbd_mc_members_relation where uid=".$uid;
	$puid=pdo_fetchcolumn($sql);
	//print("<br/>puid:".$puid);
	if(!empty($puid)){
		$remark="^一级会员贡献^";
		$members=pdo_fetch("select * from ims_mc_members where uid=".$puid);
		$integral=floatval($integral)*floatval($set['member_level1dis'])/100;
		count_user_credits($members,$integral,$remark,$puid,$uniacid);
		
		$sql="select p_uid from ims_netsbd_mc_members_relation where uid=".$puid;
		$ppuid=pdo_fetchcolumn($sql);
		if(!empty($ppuid)){
			$remark="^二级会员贡献^";
			$members=pdo_fetch("select * from ims_mc_members where uid=".$ppuid);
			$integral=floatval($integral)*floatval($set['member_level2dis'])/100;
			count_user_credits($members,$integral,$remark,$ppuid,$uniacid);
			
			$sql="select p_uid from ims_netsbd_mc_members_relation where uid=".$ppuid;
			$pppuid=pdo_fetchcolumn($sql);
			if(!empty($pppuid)){
				$remark="^三级会员贡献^";
				$members=pdo_fetch("select * from ims_mc_members where uid=".$pppuid);
				$integral=floatval($integral)*floatval($set['member_level3dis'])/100;
				count_user_credits($members,$integral,$remark,$pppuid,$uniacid);
			}
		}
	}
}

//验证用户是否已获取过
function check_user_isget($uid,$uniacid,$tag){
	//查询条件
	
	$condition = ' uniacid=:uniacid';
	$pars=array();
	$pars['uniacid']=$uniacid;
	$sql="select * from ".tablename('netsbd_set')." where ".$condition;
	$set=pdo_fetch($sql,$pars);
	
	//var_dump($pars);
	$check_tag="^新闻_";
	$gettotal=get_i($check_tag,$uid);
	/*
	//print("<br/>UID:".$uid);
	//print("<br/>TAG:".explode('_',$tag)[0]);
	//print("<br/>TAG1:".strpos($tag,"^新闻_"));
	//print("<br/>gettotal:".$gettotal);
	//print("<br/>max_click_today:".$set["max_click_today"]);
	$ishttp=strstr($tag,"^新闻_");//阅读新闻
	
	if(!empty($ishttp) && intval($gettotal)>=intval($set["max_click_today"])){
		return false;
	}
	
	$ishttp=strstr($tag,"^赞新闻_");
	if($ishttp && intval($gettotal)>=intval($set["max_good_today"])){
		return false;
	}
	$ishttp=strstr($tag,"^新闻分享被阅读_");
	if($ishttp && intval($gettotal)>=intval($set["max_share_today"])){
		return false;
	}
	$ishttp=strstr($tag,"^赞评论_");
	if($ishttp && intval($gettotal)>=intval($set["max_good_today"])){
		return false;
	}
	$ishttp=strstr($tag,"^新闻评论被赞_");
	if($ishttp && intval($gettotal)>=intval($set["max_begood"])){
		return false;
	}
	$ishttp=strstr($tag,"^赞新闻_");
	if($ishttp && intval($gettotal)>=intval($set["max_good_today"])){
		return false;
	}
	$ishttp=strstr($tag,"^新闻分享被赞_");
	if($ishttp && intval($gettotal)>=intval($set["max_begood"])){
		return false;
	}
	$ishttp=strstr($tag,"^新闻评论_");
	if($ishttp && intval($gettotal)>=intval($set["max_comment_today"])){
		return false;
	}
	$ishttp=strstr($tag,"^新闻分享被评论_");
	if($ishttp && intval($gettotal)>=intval($set["max_becomment"])){
		return false;
	}
	*/
	$sql="select * from ".tablename("mc_credits_record")." where uid=".$uid." AND uniacid=".$uniacid." AND remark LIKE '%".$tag."%'  AND FROM_UNIXTIME( createtime, '%Y-%m-%d' ) =curdate() ";
	//print($sql);
	$r=pdo_fetch($sql,$pars);
	if(empty($r)){
		return true;
	}else{
		return false;
	}
}
//到上限了也不添加
function get_i($tag,$uid){
	
	if(empty($uid) || $uid==0){
		return 0;
	}
	$get_sql="select SUM(num) from ims_mc_credits_record where uid=".$uid." AND FROM_UNIXTIME( createtime, '%Y-%m-%d' ) =curdate() AND remark like '%".$tag."%'";
	$click_i=pdo_fetchcolumn($get_sql);
	$click_i=intval($click_i);
	return $click_i;
	
}

//添加新闻的评论、赞、分享
function insert_news_comment($newsid,$type,$uid,$uniacid,$content){
	$comment1=pdo_fetch("select * from ".tablename("netsbd_news_comment")." where newsid=:newsid AND type=:type",array("newsid"=>$newsid,"type"=>$type));
	//var_dump($comment1);
	if(!empty($comment1)){
		//return 0;
	}
	//添加评论 BEGIN
	$comment["uid"]=$uid;
	$comment["uniacid"]=$uniacid;
	$comment["newsid"]=$newsid;
	$comment["comment_content"]=$content;
	$comment["type"]=$type;
	$comment["like_num"]=1;
	$comment["ishide"]=0;
	$comment["createtime"]=TIMESTAMP;
	$i=pdo_insert("netsbd_news_comment",$comment);
	if($i>0){
		//新闻数加1 BEGIN
		$news=pdo_fetch("select * from ".tablename("netsbd_news")." where id=:id",array("id"=>$newsid));
		if($type==3){//分享
			$news["share_num"]=$news["share_num"]+1;
		}
		if($type==2){//评论
			$news["comment_num"]=$news["comment_num"]+1;
		}
		if($type==1){//赞
			$news["like_num"]=$news["like_num"]+1;
		}
		if($type==4){//点击
			$news["click_num"]=$news["click_num"]+1;
		}
		pdo_update("netsbd_news",$news,array("id"=>$newsid));
		//新闻数加1 END
	}
	return $i;
}
//END. 新闻相关
//BEGIN. 活动相关
function check_partin_game($gameid,$members,$uniacid){
	//print($gameid);
	$check_sql="SELECT * FROM ".tablename("netsbd_integral_game_set")." WHERE id=:gameid";
	$r=pdo_fetch($check_sql,array("gameid" => $gameid));
	//var_dump($r);
	if(intval($r["integral_eq_game"])>intval($members["credit2"])){
		return -10001;//余额不足
	}else{
		return 1;
	}
}
function partin_game($gameid,$uid,$uniacid){
	global $_GPC, $_W;
	 
	$condition = ' uniacid=:uniacid';
	$pars=array();
	$pars['uniacid']=$_W['uniacid'];
	$sql="select * from ".tablename('netsbd_set')." where ".$condition;
	$set=pdo_fetch($sql,$pars);

	$check_sql="SELECT * FROM ".tablename("netsbd_integral_game_record")." WHERE uid=:uid AND gameid=:gameid";
	$myrecord=pdo_fetch($check_sql,array(":uid" => $uid,":gameid" => $gameid));
	if(empty($myrecord)){
		$game_record["uid"]=$uid;
		$game_record["uniacid"]=$uniacid;
		$game_record["gameid"]=$gameid;
		$game_record["prize"]="";
		$game_record["state"]=0;
		$game_record["createtime"]=TIMESTAMP;
		$i=pdo_insert("netsbd_integral_game_record",$game_record);
		//$i=1;
		if($i>0){
			//扣除会员余额
			load()-> model('mc');
			$game_sql="SELECT * FROM ".tablename("netsbd_integral_game_set")." WHERE id=:gameid";
			$game=pdo_fetch($game_sql,array(":gameid" => $gameid));
			mc_credit_update($uid, 'credit2', -1*floatval($game['integral_eq_game']),array("0","参与活动消费"));
			//发送模版消息
			if(!empty($set['template_msg1'])){
				$template_id=$set['template_msg1'];
				$data=get_gamedata("");
				$openid=$_W['fans']['from_user'];
				sendTemplateMsg($openid,$data,$template_id,$url="");
			}
			//每次有人参与活动都自动验证是否开奖
			gameover($gameid);
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//活动结束自动开奖
function gameover($gameid){
	global $_GPC, $_W;
	//活动信息
	$uniacid=$_W['uniacid'];
	$game=pdo_fetch("SELECT * FROM ".tablename('netsbd_integral_game_set')." WHERE uniacid=".$uniacid." AND id=".$gameid);
	$check_sql="SELECT * FROM ims_netsbd_integral_game_record WHERE state=1 AND gameid=:gameid";
	$check_record=pdo_fetch($check_sql,array(":gameid"=>$gameid));
	//以参与总数
	$sum=pdo_fetchcolumn("SELECT COUNT(0) FROM ".tablename('netsbd_integral_game_record')." WHERE gameid=".$gameid);
	//如果不满开奖人数则返回
	if(intval($sum)<intval($game['num_eq_result'])){
		return;
	}
	$condition = ' uniacid=:uniacid';
	$pars=array();
	$pars['uniacid']=$_W['uniacid'];
	$sql="select * from ".tablename('netsbd_set')." where ".$condition;
	$set=pdo_fetch($sql,$pars);
	
	//如果不存在中奖记录 在执行开奖
	if(empty($check_record)){
		//这里随机取出一个参与该活动的会员
		$partin_record=pdo_fetch("SELECT r1.* FROM ims_netsbd_integral_game_record AS r1 JOIN 
		( SELECT ROUND(RAND() * (SELECT MAX(id) FROM ims_netsbd_integral_game_record)) AS id) AS r2 
		WHERE r1.id >= r2.id AND r1.uniacid=:uniacid AND r1.gameid=:gameid LIMIT 0,1",array(":uniacid"=>$uniacid,"gameid"=>$gameid));
		//随机出来的用户修改为中奖状态
		$partin_record1["state"]="1";
		$i=pdo_update("netsbd_integral_game_record",$partin_record1,array("id"=>$partin_record['id']));
		//发送模版消息
		if(!empty($set['template_msg1'])){
			$openid=pdo_fetchcolumn("SELECT openid FROM ".tablename('mc_mapping_fans')." WHERE uid=".$partin_record['uid']);
			if(!empty($openid)){
				$template_id=$set['template_msg1'];
				$data=get_gameoverdata($game['title']);
				sendTemplateMsg($openid,$data,$template_id,$url="");
			}
		}
	}
}
//END. 活动相关
//BEGIN 发送模版消息
function get_wxconfig(){
	global $_GPC, $_W;
	$wxconfig['appid']=$_W['account']['key'];
	$wxconfig['appsecret']=$_W['account']['secret'];
	return $wxconfig;
}

//取得模版的data数据
function get_clickdata($name){
	//发送模版消息
	$timet=date('Y-m-d H:i',time());
		$first="分享被读";
		$keyword2="您好,你分享的新闻被阅读了";
		$keyword1="分享被读";
		$remark="查看时间".$timet;
		$template_id=$sucess_row['set_value'];
		$data= array(
			'keyword1'=>array('value'=>$keyword2,'color'=>"#743A3A"),
			'keyword2'=>array('value'=>$keyword1,'color'=>"#743A3A"),
			'remark'=>array('value'=>$timet,'color'=>"#743A3A"),
		);
		return $data;
}
function get_newmemberdata($membsername){
	//发送模版消息
	$timet=date('Y-m-d H:i',time());
		$keyword1="您好，您有下级会员注册成功。";
		$keyword2="推荐注册";
		$remark="查看时间".$timet;
		$template_id=$sucess_row['set_value'];
		$data= array(
			'keyword1'=>array('value'=>$keyword1,'color'=>"#743A3A"),
			'keyword2'=>array('value'=>$keyword2,'color'=>"#743A3A"),
		);
		return $data;
}
function get_gamedata($membsername){
	//发送模版消息
	$timet=date('Y-m-d H:i',time());
		$keyword1="您好，您已成功参与活动".$membsername;
		$keyword2="参与活动";
		$remark="查看时间".$timet;
		$template_id=$sucess_row['set_value'];
		$data= array(
			'keyword1'=>array('value'=>$keyword1,'color'=>"#743A3A"),
			'keyword2'=>array('value'=>$keyword2,'color'=>"#743A3A"),
		);
		return $data;
}	
function get_gameoverdata($name){
	//发送模版消息
	$timet=date('Y-m-d H:i',time());
		$keyword1="您好，您参与的活动".$name."中奖了，赶紧去完善资料领奖吧";
		$keyword2="活动开奖";
		$remark="时间".$timet;
		$template_id=$sucess_row['set_value'];
		$data= array(
			'keyword1'=>array('value'=>$keyword1,'color'=>"#743A3A"),
			'keyword2'=>array('value'=>$keyword2,'color'=>"#743A3A"),
		);
		return $data;
}

/*
 *发送模版消息
 */
function sendTemplateMsg($openid,$data,$template_id,$url=""){
		load()->func('communication');
		$wxconfig =get_wxconfig();
		
		//$template_id="DPtQ2hTHaFeQPhrJQ5NVxgbSMXcopX4q7F0JyiE0OrY";
		//获取token
		$oauth2_code = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $wxconfig['appid'] . "&secret=" . $wxconfig['appsecret'];
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
		$access_token=$token['access_token'];
		$json_array=array(
			"touser"=>$openid,
			"template_id"=>$template_id,//通知模版消息编号
			"url"=>$url,
			"data"=>$data
		);
		$json_template = json_encode($json_array);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $d=ihttp_post($url, $json_template);
		//var_dump($d);
}
//END   发送模版消息
//微信向用户付款  END
include $this->template('ajax_common');
?>