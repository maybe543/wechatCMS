<?php
 defined('IN_IA') or exit('Access Denied');
global $_GPC, $_W;
$from_user = $_GPC['from_user'];
yload() -> classs('quickcenter', 'fans');
$_fans = new Fans();
if (checksubmit('submit')){
    $data = array('credit1' => intval($_GPC['credit1']), 'credit2' => floatval($_GPC['credit2']), 'vip' => intval($_GPC['vip']));
    $_fans -> update($_W['weid'], $from_user, $data);
}else if (checksubmit('addcredit1-submit')){
    $data = intval($_GPC['addcredit1']);
    $_fans -> addCredit($_W['weid'], $from_user, $data, 1, "管理员后台手动");
    message('增加积分成功: +' . $data . '分', referer(), 'success');
}else if (checksubmit('subcredit1-submit')){
    $data = 0 - intval($_GPC['subcredit1']);
    $_fans -> addCredit($_W['weid'], $from_user, $data, 1, "管理员后台手动");
    message('减少积分成功: ' . $data . '分', referer(), 'success');
}else if (checksubmit('addcredit2-submit')){
    $data = floatval($_GPC['addcredit2']);
    $_fans -> addCredit($_W['weid'], $from_user, $data, 2, "管理员后台手动");
    message('余额充值成功: +' . $data . '元', referer(), 'success');
}else if (checksubmit('subcredit2-submit')){
    $data = 0 - floatval($_GPC['subcredit2']);
    $_fans -> addCredit($_W['weid'], $from_user, $data, 2, "管理员后台手动");
    message('余额扣减成功: ' . $data . '元', referer(), 'success');
}else if (checksubmit('unfollowuplevel')){
    $old_leader = trim($_GPC['old_leader']);
    yload() -> classs('quicklink', 'follow');
    $_follow = new Follow();
    $_follow -> unFollow($_W['weid'], $old_leader, $from_user);
}else if (checksubmit('changefollowuplevel')){
    $old_leader = trim($_GPC['old_leader']);
    $new_leader = trim($_GPC['new_leader']);
    $fans = $_fans -> get($_W['weid'], $new_leader);
    if (empty($fans)){
        message('修改失败。您输入的OpenID在系统中不存在, 请检查是否输错了哦。', referer(), 'error');
    }
    yload() -> classs('quicklink', 'follow');
    $_follow = new Follow();
    $ret = $_follow -> changeFollow($_W['weid'], $old_leader, $new_leader, $from_user);
    if (empty($ret)){
        message('修改上级为' . $new_leader . '之后，上下级关系可能成环. 拒绝修改', referer(), 'error');
    }
}else if (checksubmit('talktouser')){
    $msg = $_GPC['talktouser_msg'];
    $openid = $_GPC['talktowho'];
    yload() -> classs('quickcenter', 'custommsg');
    if (empty($msg)){
        message('请输入消息内容！', referer(), 'error');
    }else if (empty($openid)){
        message('请指定接收消息的用户的OpenID！', referer(), 'error');
    }
    $_custommsg = new CustomMsg();
    $ret = $_custommsg -> sendText($_W['weid'], $openid, $msg);
    message('发送消息成功！', referer(), 'success');
}else{
    print_r($_GPC);
    exit(0);
}
