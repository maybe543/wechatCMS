<?php
error_reporting(0);
define('IN_MOBILE', true);
if(empty($_GET['out_trade_no'])) {
	exit('request failed.');
}
require '../../framework/bootstrap.inc.php';
load()->app('common');
load()->app('template');
$_W['uniacid'] = $_W['weid'] = $_GET['body'];
$pid = intval($_GET['pid']);
$alipay = pdo_fetch("SELECT * FROM".tablename('xcommunity_alipayment')."WHERE uniacid=:uniacid AND pid=:pid",array(':pid' => $pid,':uniacid' => $_W['uniacid']));
// print_r($alipay);exit();

if(empty($alipay)) {
	exit('request failed.');
}
$prepares = array();
foreach($_GET as $key => $value) {
	if($key != 'sign' && $key != 'sign_type') {
		$prepares[] = "{$key}={$value}";
	}
}
sort($prepares);
$string = implode($prepares, '&');
$string .= $alipay['secret'];
$sign = md5($string);
//bug:签名不一样，后面修复
if($sign == $_GET['sign'] && $_GET['is_success'] == 'T' && $_GET['trade_status'] == 'TRADE_FINISHED') {
// if($_GET['is_success'] == 'T' && $_GET['trade_status'] == 'TRADE_FINISHED') {

	$cid = intval($_GET['cid']);
	//更新用户物业费状态
	pdo_update('xcommunity_cost_list', array('status' => '是'), array('id' => $cid));
	//更新订单状态
	$ordersn = intval($_GET['ordersn']);
	pdo_update('xcommunity_order', array('status' => 1), array('ordersn' => $ordersn));
	message('支付成功，请返回微信客户端查看订单状态', '', 'success');
} else {
	message('支付异常，请返回微信客户端查看订单状态或是联系管理员', '', 'error');
}
