<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区设置
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$id = intval($_GPC['id']);
if (checksubmit('submit')) {
		$data = array(
				'uniacid' => $_W['uniacid'],
				'code_status' => intval($_GPC['code_status']),
				'range' => intval($_GPC['range']),
				'room_status' => intval($_GPC['room_status']),
				'room_enable' => intval($_GPC['room_enable']),
				'h_status' => intval($_GPC['h_status']),
				's_status' => intval($_GPC['s_status']),
				'c_status' => intval($_GPC['c_status']),
			);
		if (empty($id)) {
			pdo_insert('xcommunity_set',$data);
		}else{
			pdo_update('xcommunity_set',$data,array('id' => $id));
		}
		message('提交成功',referer(),'success');
	}
	$settings = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

include $this->template('web/set/set');