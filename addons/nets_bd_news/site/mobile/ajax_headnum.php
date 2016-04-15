<?php
global $_GPC, $_W;
//查询条件
$uid=$_GPC["hxs_uid"];
if(empty($uid)){
	$uid=0;
}
$condition = ' uniacid=:uniacid';
$pars=array();
$pars['uniacid']=$_W['uniacid'];
$sql="select * from ".tablename('netsbd_set')." where ".$condition;
$sets=pdo_fetch($sql,$pars);
/*
$login_i=get_i("login",$uid);
$re_register_i=get_i("re_register",$uid);
$click_i=get_i("click",$uid);
$share_i=get_i("share",$uid);
$be_comment_click_i=get_i("be_comment_click",$uid);
$comment_click_i=get_i("comment_click",$uid);
$like_i=get_i("like",$uid);
$belike_i=get_i("belike",$uid);
$comment_i=get_i("comment",$uid);
$becomment_i=get_i("becomment",$uid);
*/
function get_i($tag,$uid){
	if(empty($uid) || $uid==0){
		return 0;
	}
	$click_i=pdo_fetchcolumn("select SUM(num) from ims_mc_credits_record where uid=".$uid." AND FROM_UNIXTIME( createtime, '%Y%m%d' ) =curdate() AND remark like '%^".$tag."%^'");
	$click_i=intval($click_i);
	return $click_i;
}

include $this->template('ajax_headnum');
?>