<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台支付方式设置
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$id = intval($_GPC['id']);
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'add';
$type = intval($_GPC['type']) ? intval($_GPC['type']) : 1;
if ($op == 'add') {
	
	if (checksubmit('submit')) {
		$data = array(
				'uniacid' => $_W['uniacid'],
				'pay' => serialize($_GPC['pay']),
				'type' => $_GPC['type'],
			);
		if ($id) {
			pdo_update('xcommunity_pay',$data,array('id' => $id));
		}else{
			pdo_insert('xcommunity_pay',$data);
		}
			message('提交成功',$this->createWebUrl('pay',array('op' => 'list')),'success');
	}
	if ($id) {
		$setdata = pdo_fetch("SELECT * FROM".tablename('xcommunity_pay')."WHERE id=:id",array(':id' => $id));
		$set = unserialize($setdata['pay']);
	}
	include $this->template('web/set/pay/add');
}elseif ($op == 'list') {
	$list = pdo_fetchall("select * from " . tablename('xcommunity_pay') . ' where uniacid=:uniacid', array(
	            ':uniacid' => $_W['uniacid']
	        ));
	foreach ($list as $key => $value) {
		$list[$key]['pay'] = unserialize($value['pay']);
	}
	include $this->template('web/set/pay/list');
}elseif ($op == 'delete') {
	if (empty($id)) {
		message('缺少参数',referer(),'error');
	}
	$r = pdo_delete("xcommunity_pay",array('id'=>$id));
	if ($r) {
		message('删除成功',referer(),'success');
	}
}
