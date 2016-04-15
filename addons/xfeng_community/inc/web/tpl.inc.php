<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台模板消息设置
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$id = intval($_GPC['id']);
if (checksubmit('submit')) {
		$data = array(
				'uniacid' => $_W['uniacid'],
				'shopping_tplid' => $_GPC['shopping_tplid'],
				'property_tplid' => $_GPC['property_tplid'],
				'water_tplid' => $_GPC['water_tplid'],
				'gas_tplid' => $_GPC['gas_tplid'],
				'power_tplid' => $_GPC['power_tplid'],
				'guard_tplid' => $_GPC['guard_tplid'],
				'lift_tplid' => $_GPC['lift_tplid'],
				'car_tplid' => $_GPC['car_tplid'],
				'repair_tplid' => $_GPC['repair_tplid'],
				'report_tplid' => $_GPC['report_tplid'],
				'other_tplid' => $_GPC['other_tplid'],
				'good_tplid' => $_GPC['good_tplid'],
				'grab_wc_tplid' => $_GPC['grab_wc_tplid'],
				'homemaking_tplid' => $_GPC['homemaking_tplid'],
			);
		if (empty($id)) {
			pdo_insert('xcommunity_wechat_tplid',$data);
		}else{
			pdo_update('xcommunity_wechat_tplid',$data,array('id' => $id));
		}
		message('提交成功',referer(),'success');
	}
	$settings = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));

include $this->template('web/set/tpl');