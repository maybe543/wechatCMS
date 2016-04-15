<?php
/**
 */

error_reporting(1);
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
require_once  "WxPayPubHelper/WxPayPubHelper.php";

$input = file_get_contents('php://input');
WeUtility::logging('info',"赞赏异步通知数据".$input);

$notify=new Notify_pub();
$notify->saveData($input);
$data=$notify->getData();
$setting = pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE appid=:appid limit 1", array(":appid"=>$data['appid']));
if(empty($data)){
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误");
	WeUtility::logging('info',"赞赏回复参数格式校验错误");
	exit($notify->createXml());
}

if($data['result_code'] !='SUCCESS' || $data['return_code'] !='SUCCESS') {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","参数格式校验错误");
	WeUtility::logging('info',"赞赏回复参数格式校验错误");
	exit($notify->createXml());
}

//更新表订单信息
WeUtility::logging('info',"通知订单更新");
if($notify->checkSign($setting['shkey'])) {
	//pdo_update('amouse_board_order',array("status"=>1,'notifytime'=>TIMESTAMP), array("ordersn"=>$data['out_trade_no']));
    $notify->setReturnParameter("return_code","SUCCESS");
	$notify->setReturnParameter("return_msg","OK");
	exit($notify->createXml());
} else {
	$notify->setReturnParameter("return_code","FAIL");
	$notify->setReturnParameter("return_msg","签名校验错误");
	WeUtility::logging('info',"签名校验错误");
	exit($notify->createXml());
}

WeUtility::logging('info',"赞赏回复数据".$data);




