<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端个人页面
 */

	global $_GPC,$_W;
	load()->model('mc');
	$userinfo = mc_oauth_userinfo();
	$member = mc_fetch($_W['fans']['uid'],array('mobile','credit1'));
	// $op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
	// 	load()->model('activity');
	// 	$filter = array();
	// 	$coupons = activity_coupon_owned($_W['member']['uid'], $filter);
	// 	$tokens = activity_token_owned($_W['member']['uid'], $filter);

	// 	$setting = uni_setting($_W['uniacid'], array('creditnames', 'creditbehaviors', 'uc', 'payment', 'passport'));
	// 	$behavior = $setting['creditbehaviors'];
	// 	$creditnames = $setting['creditnames'];
	// 	$credits = mc_credit_fetch($_W['member']['uid'], '*');

	// 	$title   = '我的社区';
	// 	$member = $this->changemember();
	// 	$tel = $this->linkway();
	// 	$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$member['regionid']}'");
	// 	load()->classs('weixin.account');
	// 	$obj = new WeiXinAccount();
	// 	$access_token = $obj->fetch_available_token();
	// 	$openid = $_W['openid'];
	// 	$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
	// 	load()->func('communication');
	// 	$ret = ihttp_get($url);
	// 	if(!is_error($ret)) {
	// 		$auth = @json_decode($ret['content'], true);
	// 	}	
	// $name1 = "xiaofeng_store";
	// $um1 = pdo_fetch("SELECT * FROM".tablename('uni_account_modules')."WHERE module='{$name1}' AND uniacid='{$_W['uniacid']}'");
	// if ($op == 'my') {
	// 	$title   = '修改个人信息';
	// 	$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
	// 	include $this->template('my');
	// 	exit();
	// }
	$dos = "'repair','report','fled','houselease','homemaking','car','cost','shopping','business'";
	$menus = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE uniacid =:uniacid AND do in({$dos})",array(':uniacid' => $_W['uniacid']),'do');

	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if ($styleid) {
		include $this->template('style/style'.$styleid.'/member');
	}
	