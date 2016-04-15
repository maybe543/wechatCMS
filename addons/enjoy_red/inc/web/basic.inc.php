<?php
global $_W, $_GPC;
load()->func('tpl');
$uniacid = $_W['uniacid'];
//基本设置

$item                  = pdo_fetch("select * from " . tablename('enjoy_red_reply') . " where uniacid=" . $uniacid . "");
$item['title']         = empty($item['title']) ? '一大笔金币来袭' : $item['title'];
$item['color']         = empty($item['color']) ? '#FFE8B7' : $item['color'];
$item['apic']          = empty($item['apic']) ? './addons/enjoy_red/template/mobile/images/red/apic.jpg' : $item['apic'];
$item['fpic']          = empty($item['fpic']) ? './addons/enjoy_red/template/mobile/images/jb/fpic.png' : $item['fpic'];
$item['bgpic']         = empty($item['bgpic']) ? './addons/enjoy_red/template/mobile/images/red/bgpic.jpg' : $item['bgpic'];
$item['redpic']        = empty($item['redpic']) ? './addons/enjoy_red/template/mobile/images/jb/redpic.png' : $item['redpic'];
$item['chance']        = empty($item['chance']) ? '3' : $item['chance'];
$item['share_chance']  = empty($item['share_chance']) ? '0' : $item['share_chance'];
$item['share_icon']    = empty($item['share_icon']) ? './addons/enjoy_red/template/mobile/images/red/share.jpg' : $item['share_icon'];
$item['share_title']   = empty($item['share_title']) ? '大笔现金' : $item['share_title'];
$item['share_content'] = empty($item['share_content']) ? '一大笔金币来袭，翻到你爽' : $item['share_content'];
$item['rule']          = empty($item['rule']) ? '' : $item['rule'];
$item['color']         = empty($item['color']) ? '#fff' : $item['color'];
$item['vnum']          = empty($item['vnum']) ? '0' : $item['vnum'];
$item['unit']          = empty($item['unit']) ? '次机会' : $item['unit'];
$item['times']         = empty($item['times']) ? '200' : $item['times'];

//提交
if (checksubmit('submit')) {
    //判断是否已经存在这个活动
    $exist = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_reply') . " where uniacid=" . $uniacid . "");
    $data  = array(
        'uniacid' => $uniacid,
        'title' => $_GPC['title'],
        'state' => trim($_GPC['state']),
        'city' => trim($_GPC['city']),
        'color' => $_GPC['color'],
        'apic' => $_GPC['apic'],
        'fpic' => $_GPC['fpic'],
        'bgpic' => $_GPC['bgpic'],
        'redpic' => $_GPC['redpic'],
        'chance' => $_GPC['chance'],
        'sucai' => $_GPC['sucai'],
        'share_chance' => $_GPC['share_chance'],
        'share_icon' => $_GPC['share_icon'],
        'share_title' => $_GPC['share_title'],
        'share_content' => $_GPC['share_content'],
        'adept' => $_GPC['adept'],
        'vnum' => $_GPC['vnum'],
        'vmin' => $_GPC['vmin'],
        'vmax' => $_GPC['vmax'],
        'rule' => $_GPC['rule'],
        'subscribe' => $_GPC['subscribe'],
        'unit' => $_GPC['unit'],
        'times' => $_GPC['times'],
        'custom' => trim($_GPC['custom']),
        'cashgz' => trim($_GPC['cashgz']),
        'redpic1' => trim($_GPC['redpic1']),
        'redpic2' => trim($_GPC['redpic2']),
        'redpic3' => trim($_GPC['redpic3']),
        'redpic4' => trim($_GPC['redpic4']),
        'redpic5' => trim($_GPC['redpic5']),
        'redpic6' => trim($_GPC['redpic6']),
        // 			'prize_way'=>$_GPC['prize_way'],
        // 			'prize_place'=>$_GPC['prize_place'],
        // 			'prize_tel'=>$_GPC['prize_tel'],
        'stime' => $_GPC['stime'],
        'etime' => $_GPC['etime']
    );
    if ($exist > 0) {
        //update
        $res     = pdo_update('enjoy_red_reply', $data, array(
            'uniacid' => $uniacid
        ));
        $message = "更新活动成功";
        
    } else {
        //插入数据库
        $res     = pdo_insert('enjoy_red_reply', $data);
        $message = "新增活动成功";
        
    }
    
    
    if ($res == 1) {
        message($message, $this->createWebUrl('basic'), 'success');
    }
    
    
    
}






include $this->template('basic');