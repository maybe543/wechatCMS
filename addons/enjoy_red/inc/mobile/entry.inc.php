<?php
global $_W, $_GPC;
$modulePublic = '../addons/enjoy_red/public/';
require_once MB_ROOT . '/controller/Fans.class.php';
require_once MB_ROOT . '/controller/Act.class.php';

$fans = new Fans();
$act  = new Act();
$puid = intval($_GET['puid']);
//授权登录，获取粉丝信息
$user = $this->auth($puid);
// $user['openid']="omWcNsy_PGp_BI9ZX_sDHNv0i1io";
// $user['uniacid']="5";

//取活动信息
$actdetail = $act->getact();
if ($actdetail['custom'] == 0) {
    $actdetail['redpic1'] = $actdetail['fpic'];
    $actdetail['redpic2'] = $actdetail['fpic'];
    $actdetail['redpic3'] = $actdetail['fpic'];
    $actdetail['redpic4'] = $actdetail['fpic'];
    $actdetail['redpic5'] = $actdetail['fpic'];
    $actdetail['redpic6'] = $actdetail['fpic'];
}
if (empty($puid)) {
    pdo_query("update " . tablename('enjoy_red_fans') . " set subscribe=1 where uniacid=" . $user['uniacid'] . " and openid='" . $user['openid'] . "'");
} else {
    $popenid = pdo_fetchcolumn("select openid from " . tablename('enjoy_red_fans') . " where uniacid=" . $user['uniacid'] . " and uid=" . $puid . "");
    //机会增加
    if ($puid != $user['uid']) {
        //先判断自己是否已经打开过连接了，
        $shareC = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_chance_log') . " where openid='" . $user['openid'] . "' and puid=" . $puid . " and uniacid=" . $user['uniacid'] . "");
        if ($shareC > 0) {
            //已经分享过了
        } else {
            //查询这个人已经有的记录
            $count = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_chance_log') . " where uniacid=" . $user['uniacid'] . " and openid='" . $popenid . "'");
            if ($count <= $actdetail['times']) {
                $chance = pdo_fetchcolumn("select chance from " . tablename('enjoy_red_chance') . " where uniacid=" . $user['uniacid'] . " and openid='" . $popenid . "'");
                
                $chance = $chance + $actdetail['share_chance'];
                //pdo_query("update ".tablename('enjoy_red_chance')." set chance=chance+".$actdetail['share_chance']." where uniacid=".$user['uniacid']." and openid='".$popenid."'");
                pdo_update('enjoy_red_chance', array(
                    'chance' => $chance
                ), array(
                    'uniacid' => $user['uniacid'],
                    'openid' => $popenid
                ));
                //插入机会表
                $data = array(
                    'uniacid' => $user['uniacid'],
                    'openid' => $user['openid'],
                    'puid' => $puid,
                    'chance' => $actdetail['share_chance'],
                    'createtime' => TIMESTAMP
                    
                );
                pdo_insert('enjoy_red_chance_log', $data);
            }
        }
    }
    
}
// var_dump($user);
// exit();
// session_start();
// var_dump($_SESSION['openid']);

// exit();
// $user['uniacid']=5;
// $user['openid']=$_W['openid'];


//活动参加人数
$countP = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_fans') . " where uniacid=" . $user['uniacid'] . "");
$countP = $countP + $actdetail['vnum'];

//查询游戏是否已经截止了
if (strtotime($actdetail['etime']) < TIMESTAMP) {
    //游戏已结束
    $flag = 1;
} elseif (strtotime($actdetail['stime']) > TIMESTAMP) {
    //游戏未开始
    $flag = 2;
} else {
    $flag  = 0;
    //查询表里是否有本人的机会
    $count = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_chance') . " where uniacid=" . $user['uniacid'] . " and openid='" . $user['openid'] . "'");
    if ($count < 1) {
        //插入机会数据
        $data = array(
            'uniacid' => $user['uniacid'],
            'openid' => $user['openid'],
            'chance' => $actdetail['chance'],
            'createtime' => TIMESTAMP
        );
        pdo_insert('enjoy_red_chance', $data);
    }
    //我的机会
    $chance = pdo_fetchcolumn("select chance from " . tablename('enjoy_red_chance') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . "");
    
    //我的奖池
    $countM = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . "");
    if (empty($countM)) {
        $countM = 0;
    }
    //累计的钱
    $countL = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where openid='" . $user['openid'] . "' and uniacid=" . $user['uniacid'] . " and money>0");
    if (empty($countL)) {
        $countL = 0;
    }
    //传6个虚拟数回去
    $reply      = $act->getact();
    $rand_array = range($reply['vmin'], $reply['vmax']);
    shuffle($rand_array); //调用现成的数组随机排列函数
    $vmoney = array_slice($rand_array, 0, 6); //截取前6个
    
}

//分享信息
$sharelink = $_W['siteroot'] . "app/" . $this->createMobileUrl('entry', array(
    'puid' => $user['uid']
));



include $this->template('entry');