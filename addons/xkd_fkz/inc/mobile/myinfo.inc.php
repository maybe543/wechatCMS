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

$ops = array('display', 'child');
$op = $_GPC['op'];
$op = in_array($op, $ops) ? $op : 'display';

$uid = $_W['member']['uid'];
$cfg = $this->module['config'];

$member = $mod_member->get_member($uid);


if (empty($member['parent1']) && $uid != $cfg['uid']) {
	include $this->template('nolevel');
	return;
}

if ($op == 'display') {

	$uid = $_W['member']['uid'];
	$packet =$mod_record->get_packet($uid);
	$flag = '';
	$child_list = array();
	for($i = 1; $i <= $member['level']; $i++) {
		$child_list[$i] = $mod_member->get_children_count($uid, $i);		
	}
	
	if ($member['level'] >= $cfg['level']) {
		$flag = 0;
	} else {
		$flag = 1;
		
		$children_count = $mod_member->get_children_count($uid, $member['level'] + 1);
		if ($children_count > 0) {
			$flag = 2;
		}
	}
	
	$junior_list = $mod_member->get_junior_list();
	include $this->template('myinfo');
}

elseif ($op == 'child') {
	
	$parent_name = $_GPC['parent_name'];
	$child_level = $_GPC['child_level'];
	$parentid = $_GPC['parentid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$total = 0;
	
	$filter = array();
	$filter['child_level'] = $child_level;
	$filter['parentid'] = $parentid;
	$list = $mod_member->get_member_page($filter, $order = 'add_time desc', $pindex, $psize, $total);
	$level_list = $mod_member->get_level_list();
	
	foreach ($list as $k => $v) {
		$list[$k]['level_text'] = $level_list[$v['level']]['name'];
	}
	
	$pager = pagination($total, $pindex, $psize);
	$junior_list = $mod_member->get_junior_list();
	include $this->template('leveluser');
}
?>
