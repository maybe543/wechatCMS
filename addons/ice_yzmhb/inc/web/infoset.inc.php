<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;

load()->func('tpl');
//这里来展示设置项表单
$modulelist = uni_modules(false);
$foo ='infoset';
$rid =0;
$hbid =0;

if(empty($_GPC['id']))
	$hbid = $_GPC['hbid'];
else
	$hbid = pdo_fetchcolumn('select id from '.tablename('ice_yzmhb').' where rid =:rid',array(':rid' =>$_GPC['id']));

if(checksubmit()){
	load()->func('logging');
	logging_run('进入到这里:'.json_encode($_GPC),'','here1');
	/*
	$data['content'] = $_GPC['content'];
	$data['stime'] = $_GPC['stime'];
	$data['etime'] = $_GPC['etime'];
	$data['nick_name'] = $_GPC['nick_name'];
	$data['send_name'] = $_GPC['send_name'];
	$data['min_value'] = $_GPC['min_value'];
	$data['max_value'] = $_GPC['max_value'];
	$data['total_num'] = $_GPC['total_num'];
	$data['wishing'] = $_GPC['wishing'];
	$data['act_name'] = $_GPC['act_name'];
	$data['logo_imgurl'] = $_GPC['logo_imgurl'];
	$data['share_content'] = $_GPC['share_content'];
	$data['share_url'] = $_GPC['share_url'];
	$data['share_imgurl'] = $_GPC['share_imgurl'];
	*/
	$data['act_name'] = $_GPC['data']['act_name'];
	$data['wishing'] = $_GPC['data']['wishing'];
	$data['send_name'] = $_GPC['data']['send_name'];
	$data['stime'] = $_GPC['data']['stime'];
	$data['etime'] = $_GPC['data']['etime'];
	$data['min_value'] = $_GPC['data']['min_value'];
	$data['max_value'] = $_GPC['data']['max_value'];
	$data['remark'] = $_GPC['data']['remark'];
	$ds = pdo_update('ice_yzmhb',$data,array('id'=>$hbid));
	logging_run('进入到这里:'.$ds,'','ds');
	if($ds >0)
		 message('设置成功',  referer(), 'success');
}
$settings = pdo_fetch('select * from '.tablename('ice_yzmhb').' where id =:id',array(':id' =>$hbid));
load()->func('logging');
	logging_run('进入到这里:'.json_encode($setting),'','here2');

include $this->template('infosettings');
/*
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
*/

	




















