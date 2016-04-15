<?php
defined('IN_IA') or exit('Access Denied');

global $_W,$_GPC;
$this->loadMod('record');
$this->loadMod('member');
$mod_record = new record();
$mod_member = new member();
load()->func('tpl');

$op = $_GPC['op'];
$ops = array('display','approval');
$op = in_array($op, $ops) ? $op : 'display';

if ($op == 'display') {
	
	if(!empty($_GPC['apply'])) {
		$apply_start_time = strtotime($_GPC['apply']['start']);
		$apply_end_time = strtotime($_GPC['apply']['end']);
	}else {
		$apply_start_time = strtotime('-1 week');
		$apply_end_time = TIMESTAMP;
	}
	$apply['start'] = date('Y-m-d H:i:s', $apply_start_time);
	$apply['end'] = date('Y-m-d H:i:s', $apply_end_time);
	
	
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$total = 0;
	
	$filter = array();
	$filter['apply_member'] = $_GPC['apply_member'];
	$filter['approval_member'] = $_GPC['approval_member'];
	
	$filter['apply']['start_time'] = $apply_start_time;
	$filter['apply']['end_time'] = $apply_end_time;
	
	$list = $mod_record->get_record_page($filter, $order = 'r.apply_time desc', $pindex, $psize, $total);
	$pager = pagination($total, $pindex, $psize);
	include $this->template('record');
}

elseif ($op == 'approval') {
	
	$record_id = $_GPC['record_id'];
	$record = $mod_record->get_record($record_id);
	if (empty($record)) {
		message('该升级申请记录不存在!',referer,'warning');
	}
	$data = array(
		'a_flag' => 2,
		'm_flag' => 2,
		'approval_time' => TIMESTAMP, 	
	);
	$mod_record->update_record($record_id, $data);
	$mod_member->update_member_field($record['apply_uid'], 'level', $record['upgrade']);
	
	$applicant = $mod_member->get_member($record['apply_uid']);
	$acid = $_W['uniacid'];
	$acc = WeAccount::create($acid);
	$send = array(
		'touser' => $applicant['openid'],
		'msgtype' => 'text',
		'text' => array(
			'content' => urlencode('恭喜，您的升级申请已通过')
		)
	);
	$acc->sendCustomNotice($send);
	
	message('该升级申请记录审批成功!',referer,'success');
}
?>