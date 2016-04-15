<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 注册短信验证
 */

	global $_GPC,$_W;
	load()->classs('wesession');
	WeSession::start($_W['uniacid'],$_W['fans']['from_user'],60);
	$mobile = $_GPC['mobile'];
	if ($_GPC['type'] == 'verify') {
		load()->model('mc');
		$r = mc_check(array('mobile' => $mobile));

		//$member = pdo_fetch("select * from".tablename("xcommunity_member")."where uniacid='{$_W['uniacid']}' and mobile=:mobile",array(':mobile' => $mobile));
	}else{
		$r = pdo_fetch("select * from".tablename("xcommunity_business")."where uniacid='{$_W['uniacid']}' and mobile=:mobile",array(':mobile' => $mobile));
	}
	//判断会员是否存在
	if (empty($r)) {
		//会员不存在
		$result = array(
				'status' => 2,
			);
		echo json_encode($result);exit();
	}else{
		if($mobile==$_SESSION['mobile']){
			$code=$_SESSION['code'];
		}else{
			$code= random(6,1);
			$_SESSION['mobile']=$mobile;
			$_SESSION['code']=$code;
		}
		$sms = pdo_fetch("SELECT resgisterid,verifycode,sms_account FROM".tablename('xcommunity_wechat_smsid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		if ($sms['verifycode']) {
			//验证是否开启	
			$mobile    = $_SESSION['mobile'];
			$tpl_id    = $sms['resgisterid'];
			$tpl_value = urlencode("#code#=$code");
			$appkey    = $sms['sms_account'];
			$params    = "mobile=".$mobile."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
			$url       = 'http://v.juhe.cn/sms/send';
			load()->func('communication');
			$content   = ihttp_post($url,$params);
			return $content;
		}
		
	}
	
