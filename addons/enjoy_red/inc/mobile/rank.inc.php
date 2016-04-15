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
if (empty($county)) {
    $county = 0;
}
//累计的钱
$countm = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . " and money>0");
if (empty($countm)) {
    $countm = 0;
}
//取出活动前几名
$ranks = pdo_fetchall("select a.*,SUM(b.money) as sum from " . tablename('enjoy_red_fans') . " as a left join " . tablename('enjoy_red_log') . " as b on a.openid=b.openid
		where b.uniacid=" . $user['uniacid'] . " and a.uniacid=" . $user['uniacid'] . " and b.money>0 group by b.openid order by SUM(b.money) desc LIMIT 20");
// var_dump($ranks);
// exit();
//循环递增出一个数组json
for ($i = 0; $i < count($ranks); $i++) {
    // 	{"img": "http://stc.weimob.com/img/magpiebridge/icon_head_empty.png", "name": "sxk", "score": 3.79}
    $str .= '{"img":"' . $ranks[$i][avatar] . '","name":"' . $ranks[$i][nickname] . '","score":' . $ranks[$i][sum] . '},';
}
// var_dump($str);
// exit();













include $this->template('rank');