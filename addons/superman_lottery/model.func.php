<?php
/**
 * 【超人】关键字抽奖模块
 *
 * @author 超人
 * @url
 */
defined('IN_IA') or exit('Access Denied');

function superman_get_credits() {
    global $_W;
    $credits = array();
    $credits['credit1'] = array('enabled' => 0, 'title' => '');
    $credits['credit2'] = array('enabled' => 0, 'title' => '');
    $credits['credit3'] = array('enabled' => 0, 'title' => '');
    $credits['credit4'] = array('enabled' => 0, 'title' => '');
    $credits['credit5'] = array('enabled' => 0, 'title' => '');
    $list = pdo_fetch("SELECT creditnames FROM ".tablename('uni_settings') . " WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
    if(!empty($list['creditnames'])) {
        $list = iunserializer($list['creditnames']);
        if(is_array($list)) {
            foreach($list as $k => $v) {
                $credits[$k] = $v;
            }
        }
    }
    return $credits;
}