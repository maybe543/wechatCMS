<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区活动
 */
	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$menu = $this->NavMenu();
	$url = $menu[0]['items'][0]['url'];
	header("location: " . $url);