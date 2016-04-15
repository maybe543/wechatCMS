<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端我的小区
 */
global $_W,$_GPC;
$member = $this->changemember();
$region = $this->mreg();
$region = pdo_fetch("SELECT r.* ,p.title as ptitle,p.content as pcontent FROM".tablename('xcommunity_region')."as r left join ".tablename('xcommunity_property')."as p on r.pid = p.id WHERE r.id=:id",array(':id' => $member['regionid']));

$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
if ($styleid) {
	include $this->template('style/style'.$styleid.'/myregion');exit();
}