<?php
global $_W, $_GPC;
$modulePublic = '../addons/enjoy_red/public/';
require_once MB_ROOT . '/controller/Fans.class.php';
require_once MB_ROOT . '/controller/Act.class.php';

$fans = new Fans();
$act  = new Act();
$puid = '';
//授权登录，获取粉丝信息
$user = $this->auth($puid);
// $user['openid']="omWcNs4YdxWrGcUswqMlg2Nwk7nc";
// $user['uniacid']="5";

//取活动信息
$actdetail = $act->getact();
//提现
$county    = pdo_fetchcolumn("select ABS(sum(money)) from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . " and money<0");
//累计的钱
$countm    = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . " and money>0");

$mylog = pdo_fetchall("select * from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . " order by createtime desc");













include $this->template('mylog');