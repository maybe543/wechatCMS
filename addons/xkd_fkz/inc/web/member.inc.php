<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;

$this->loadMod('member');
$mod_member = new member();

$this->loadMod('record');
$mod_record = new record;

$ops = array('display', 'post', 'packet', 'child');
$op = $_GPC['op'];
$op = in_array($op, $ops) ? $op : 'display';

if ($op == 'display' || $op == 'child') {
	
	$parent_name = $_GPC['parent_name'];
	$child_level = $_GPC['child_level'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$total = 0;
	
	$filter = array();
	$filter['uid'] = intval($_GPC['uid']);
	$filter['nickname'] = trim($_GPC['nickname']);
	$filter['wqm_member'] = trim($_GPC['wqm_member']);
	$filter['child_level'] = $_GPC['child_level'];
	$filter['parentid'] = $_GPC['parentid'];
	
	$list = $mod_member->get_member_page($filter, $order = 'add_time desc', $pindex, $psize, $total);
	$member_list = array();
	foreach ($list as $key => $value) {
		$member_list[$key] = $value;
		for($i = 1; $i <13; $i++) {
			$member_list[$key]["parentcount$i"] = $mod_member->get_children_count($value['uid'], $i);
			$member_list[$key]['parentcount'] += $member_list[$key]["parentcount$i"];
			if ($member_list[$key]["parentcount$i"] == 0) {
				if ($member_list[$key]['parentcount'] == 0) {
					$member_list[$key]['parentcount'] = '<br>没有下级';
					continue;
				}
			}
		}
	}
	$pager = pagination($total, $pindex, $psize);
	$junior_list = $mod_member->get_junior_list();
}

elseif ($op == 'post') {
	
	$uid = $_GPC['uid'];
	$member = $mod_member->get_member($uid);
	$levellist = $mod_member->get_level_list();
	if ($_W['ispost']) {
		$entity = array(
			'wechat' => $_GPC['wechat'],
			'qq'	 => $_GPC['qq'],
			'mobile' => $_GPC['mobile'],
			'level'	 => $_GPC['level']
		);
		$mod_member->update_member($uid, $entity);
		message('会员信息编辑成功', $this->createWebUrl('member'), 'success');
	}
}

elseif ($op == 'packet') {
	$uid = $_GPC['uid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$total = 0;
	
	$filter = array();
	$filter['apply_member'] = $_GPC['apply_member'];
	$filter['flag'] = 2;
	$packet_list = $mod_record->get_record_by_approval_uid($uid, $filter, $pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);
}

include $this->template('member');
?>