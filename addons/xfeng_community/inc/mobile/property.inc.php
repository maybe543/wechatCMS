<?php
/**
 * 微小区模块
 *
 */
/**
 * 微信端团队介绍
 */
defined('IN_IA') or exit('Access Denied');

	global $_W,$_GPC;
	$title = '物业团队介绍';
	//判断是否注册，只有注册后，才能进入
	$this->changemember();
	$list = pdo_fetch("SELECT * FROM".tablename('xcommunity_property')."WHERE weid='{$_W['weid']}'");
	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if ($styleid) {
	include $this->template('style/style'.$styleid.'/property/property');exit();
	}