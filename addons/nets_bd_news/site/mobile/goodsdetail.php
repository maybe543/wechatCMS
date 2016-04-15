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
$good=pdo_fetch("select * from ims_activity_exchange where id=:id AND uniacid=".$_W['uniacid'],array(":id"=>$_GPC["id"]));
if(!empty($_GPC["buy"])){
	if($good["total"] <= 0) {
		message('支付错误, 金额小于0');
	}
	/*
	$mc_credits_record["uid"]=$_W['member']['uid'];
	$mc_credits_record["uniacid"]=$_W['uniacid'];
	$mc_credits_record["credittype"]="credit2";
	$mc_credits_record["num"]=-$good["total"];
	$mc_credits_record["operator"]=$_W['member']['uid'];
	$mc_credits_record["createtime"]=TIMESTAMP;
	$mc_credits_record["remark"]="购买商品".$good['title']."消费".$good["total"]."元";
	$i=pdo_insert("mc_credits_record",$mc_credits_record);
	*/
	$i=1;
	if($i>0){
		//构造支付请求中的参数
		$params = array(
			'tid' => "hxs".date("Y-m-d",TIMESTAMP).TIMESTAMP,      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
			'ordersn' => date("Y-m-d",TIMESTAMP).TIMESTAMP,  //收银台中显示的订单号
			'title' => "购买商品[".$good['title']."]",          //收银台中显示的标题
			'fee' => $good["credit"],      //收银台中显示需要支付的金额,只能大于 0
			'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
		);
		//调用pay方法
		$this->pay($params);
	}
}

include $this->template('goodsdetail');
?>