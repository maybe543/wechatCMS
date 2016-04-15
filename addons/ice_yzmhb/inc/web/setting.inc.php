<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;
//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
if(checksubmit()) {
	// $_GPC 可以用来获取 Cookies,表单中以及地址栏参数
	$dat = $_GPC['data'];
	load()->func('file');
	$appid=$dat['appid'];
	$apiclient_cert=$dat['apiclient_cert'];
	$apiclient_key=$dat['apiclient_key'];
	$rootca=$dat['rootca'];

	
	file_write("./certs/index.html", "");
	file_write("./certs/".$appid."apiclient_cert.pem", $apiclient_cert);
	file_write("./certs/".$appid."apiclient_key.pem", $apiclient_key);
	file_write("./certs/".$appid."rootca.pem", $rootca);
	
	
	//字段验证, 并获得正确的数据$dat
	if (!$this->saveSettings($dat)){
		message('保存信息失败','','error');   // 保存失败
	}else {
		message('保存信息成功','','success'); // 保存成功
	}
	
	
	
}

load()->func('tpl');
//这里来展示设置项表单
$modulelist = uni_modules(false);
$name = 'ice_yzmhb';
$module = $modulelist[$name];
if(empty($module)) {
	message('抱歉，你操作的模块不能被访问！');
}
define('CRUMBS_NAV', 1);
$ptr_title = '参数设置';
$module_types = module_types();
define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));

$settings = $module['config'];

include $this->template('settings');
	




















