<?php
require_once MB_ROOT . '/controller/Act.class.php';
require_once MB_ROOT . '/controller/Fans.class.php';

global $_W, $_GPC;
$uniacid = $_W['uniacid'];
$openid  = $_GPC['openid'];

//查询剩余奖品列表
$rulelist = pdo_fetchall("select id,rchance from " . tablename('enjoy_red_rule') . " where uniacid=" . $uniacid . " and rcount>0");
//echo $rulelist[0]['rchance'];
if (!empty($rulelist)) {
    foreach ($rulelist as $k => $v) {
        $prize_arr[$v[id]] = $v[rchance];
        $sum += $v['rchance'];
    }
    $prize_arr['0'] = 100 - $sum;
} else {
    $prize_arr['0'] = 100;
}
//判断是否关注
$act       = new Act();
$actdetail = $act->getact();
$fan       = new Fans();
$faninfo   = $fan->getOne($openid, true);
// var_dump($faninfo);
//判断是否有位置限制
if (!empty($actdetail['state']) && !empty($faninfo['state'])) {
    $valid = false;
    
    
    if (!empty($actdetail['state']) && !empty($actdetail['city'])) {
        if ($actdetail['state'] == $faninfo['state'] && $actdetail['city'] == $faninfo['city']) {
            $valid = true;
        }
    } elseif (!empty($actdetail['state'])) {
        if ($actdetail['state'] == $faninfo['state']) {
            $valid = true;
        }
    }
    
    
    if (!$valid) {
        //	return error(-3, "<h4>你的位置是: {$verifyParams['user']['state']}-{$verifyParams['user']['city']}</h4><br><h5>不在本次活动范围. 请期待我们下一次活动</h5>");
        $res['user']['state'] = $faninfo['state'];
        $res['user']['city']  = $faninfo['city'];
        $res['type']          = -6;
        echo json_encode($res);
        exit();
    }
}


if ($actdetail['subscribe'] == 1) {
    if ($faninfo['subscribe'] == 0) {
        $res['type'] = -4;
        //还没有关注
        echo json_encode($res);
        exit();
    }
    
    
}

//是否还有兑奖机会
$chance = pdo_fetchcolumn("select chance from " . tablename('enjoy_red_chance') . " where uniacid=" . $uniacid . " and openid='" . $openid . "'");
if ($chance > 10000) {
    pdo_update('enjoy_red_chance', array(
        'chance' => 0
    ), array(
        'uniacid' => $uniacid,
        'openid' => $openid
    ));
}
if ($chance < 1) {
    //机会用完了
    $res['type'] = -1;
    $res['unit'] = $actdetail['unit'];
    echo json_encode($res);
    exit();
} else {
    $rid                  = $this->getrand($prize_arr);
    $res['type']          = 1;
    //搜索奖品信息
    $res['rule']          = pdo_fetch("select * from " . tablename('enjoy_red_rule') . " where uniacid=" . $uniacid . " and id=" . $rid . "");
    $res['rule']['rpic']  = empty($res['rule']['rpic']) ? "../addons/enjoy_red/template/mobile/images/break.png" : tomedia($res['rule']['rpic']);
    $res['rule']['rname'] = empty($res['rule']['rname']) ? "空空如也" : $res['rule']['rname'];
    if ($rid > 0) {
        //先检查红包间隔秒数
        // 		$last_time=pdo_fetchcolumn("select createtime from ".tablename('enjoy_red_log')." where uniacid=".$uniacid." and openid='".$openid."'
        // 				order by createtime desc" );
        // 		$time=TIMESTAMP-$last_time;
        // 		if($time<=22){
        // 			//间隔不足20秒
        // 			$res['type']=-7;
        // 			echo json_encode($res);
        // 			exit();
        // 		}
        //红包金额
        $fee                  = rand($res['rule']['rmin'], $res['rule']['rmax']);
        $res['rule']['money'] = $fee * 0.01;
        $uniacid              = $_W['uniacid'];
        
        
        //纪录到表里里
        // 			$data=array(
        // 				'uniacid'=>$uniacid,
        // 				'openid'=>$openid,
        // 				'money'=>$res['rule']['money'],
        // 				'createtime'=>TIMESTAMP
        // 			);
        // 			$resb=pdo_insert('enjoy_red_back',$data);
        // 			if($resb==1){
        //机会减一
        //pdo_query("update ".tablename('enjoy_red_chance')." set chance=chance-1 where uniacid=".$uniacid." and openid='".$openid."'");
        //红包个数--
        pdo_query("update " . tablename('enjoy_red_rule') . " set rcount=rcount-1 where uniacid=" . $uniacid . " and id=" . $rid . "");
        //计数
        
        $insert = array(
            'uniacid' => $uniacid,
            'openid' => $openid,
            'money' => $res['rule']['money'],
            'createtime' => TIMESTAMP
        );
        $ress   = pdo_insert('enjoy_red_log', $insert);
        if ($ress == 1) {
            //机会减一
            pdo_query("update " . tablename('enjoy_red_chance') . " set chance=chance-1 where uniacid=" . $uniacid . " and openid='" . $openid . "'");
            //我的机会
            $res['chance'] = pdo_fetchcolumn("select chance from " . tablename('enjoy_red_chance') . " where uniacid=" . $uniacid . " and openid='" . $openid . "'");
            
            //我的奖池
            $res['countm'] = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where uniacid=" . $uniacid . " and openid='" . $openid . "'");
            if ($res['countm'] <= 0) {
                $res['countm'] = 0;
            }
            //累计的钱
            $res['countl'] = pdo_fetchcolumn("select sum(money) from " . tablename('enjoy_red_log') . " where openid='" . $openid . "' and uniacid=" . $uniacid . " and money>0");
        }
        //	}
        
        //}
        
        
    } else if ($rid == 0) {
        $res['type'] = -5;
        //机会减一
        pdo_query("update " . tablename('enjoy_red_chance') . " set chance=chance-1 where uniacid=" . $uniacid . " and openid='" . $openid . "'");
        //我的机会
        $res['chance'] = pdo_fetchcolumn("select chance from " . tablename('enjoy_red_chance') . " where uniacid=" . $uniacid . " and openid='" . $openid . "'");
        
        
    }
    
    
    
    
}


echo json_encode($res);
exit();