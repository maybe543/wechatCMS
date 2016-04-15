<?php
/**
 * 【超人】关键字抽奖模块
 *
 * @author 超人
 * @url
 */
defined('IN_IA') or exit('Access Denied');

function superman_sign_key($data, $sign_key) {
    ksort($data);
    $data_str = '';
    foreach ($data as $k=>$v) {
        if ($v == '' || $k == 'sign') {
            continue;
        }
        $data_str .= "$k=$v&";
    }
    $data_str .= "key=".$sign_key;
    $sign = strtoupper(md5($data_str));
    return $sign;
}