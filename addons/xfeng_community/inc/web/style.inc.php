<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台菜单和风格设置
 */

	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$data = array(
				'uniacid' => $_W['uniacid'],
				'styleid' => $_GPC['styleid'],
			);
	$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if (checksubmit('submit')) {
			if (empty($item)) {
				pdo_insert('xcommunity_template',$data);
			}else{
				$row = pdo_query("UPDATE ".tablename('xcommunity_template')."SET styleid = '{$_GPC['styleid']}' WHERE uniacid='{$_W['uniacid']}'");
				if ($row) {
					message('操作成功',referer(),'success');
				}
			}
	}
	include $this->template('web/style/style');