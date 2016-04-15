<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区商家
 */
	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();

	include $this->template('web/control/list');