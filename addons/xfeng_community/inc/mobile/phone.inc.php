<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端常用号码
 */
	global $_GPC,$_W;   	
	$title = '便民号码';
	$member = $this->changemember();
	$region = $this->mreg();
	$pindex = max(1, intval($_GPC['page']));
	$psize = 10;
	$sql    = "select * from ".tablename("xcommunity_phone")."where weid='{$_W['weid']}' order by displayorder asc";
	$list = pdo_fetchall($sql);
	$phones = array();
	if ($list) {
		foreach ($list as $key => $value) {
			$regions = unserialize($value['regionid']);
			if (@in_array($member['regionid'], $regions)) {
				$phones[$key]['phone'] = $value['phone'];
				$phones[$key]['content'] = $value['content'];
				$phones[$key]['thumb'] = $value['thumb'];
 			}
		}
	}
	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
	if ($styleid) {
		include $this->template('style/style'.$styleid.'/phone');exit();
	}