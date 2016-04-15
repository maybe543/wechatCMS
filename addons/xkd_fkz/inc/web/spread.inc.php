<?php
defined('IN_IA') or exit('Access Denied');
global $_W, $_GPC;

$this->loadMod('poster');

$mod_poster = new poster();


load()->func('tpl');

$poster_id = $_GPC['poster_id'];

if ($_W['ispost']) {
	$path_parts = explode('.', $_GPC['bg']);
	$suffix = end($path_parts);
	if (strcasecmp('jpg', $suffix) != 0) {
		message('海报背景图必须是jpg格式。不支持png等其他格式。', referer(), 'error');
	}
	if (strpos($_GPC['bg'], 'http://') === FALSE) {
		// valid
	} else {
		message('海报背景图必须从本地上传，不能使用网络图片。您可以先将网络图片保存到本地，然后再上传。', referer(), 'error');
	}
		
	$bgparam = $mod_poster->encode_poster_param($_GPC);
	
	$entity = array(
		'follow' => $_GPC['follow'],
		'createtime' => time(),
		'notmember' => $_GPC['notmember'],
		'bg' => $_GPC['bg'],
		'bgparam' => $bgparam,
		'uniacid' => $_W['uniacid']
	);
	
	if (! empty($poster_id)) {

		$mod_poster->update_poster($poster_id, $entity);
		
		message('更新海报成功', referer(), 'success');
	} else {
		$entity['active'] = 1;
		
		$mod_poster->add_poster($entity);
		message('新建海报成功', referer(), 'success');
	}
}

$item = $mod_poster->get_poster_by_uniacid();

include $this->template('spread');
?>