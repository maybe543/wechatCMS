<?php

if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
	exit('请在微信中浏览');
}


defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;
$this->loadMod('member');
$mod_member = new member();

$this->loadMod('record');
$mod_record = new record();

$pindex = max(1, intval($_GPC['page']));
$psize = 10;
$total = 0;

$filter = array();
$filter['apply_uid'] = $_GPC['apply_uid'];
$filter['flag'] = 2;
$list = $mod_record->get_record_by_approval_uid($_W['member']['uid'], $filter, $pindex, $psize, $total);
$pager = pagination($total, $pindex, $psize);
include $this->template('upok');
?>
