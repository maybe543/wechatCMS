<?php 
require MODULE_ROOT.'/model.php';
global $_W,$_GPC;
$handlers = array('添加地推人员'=>'addnew');
$operation = isset($_GPC['op']) && in_array($_GPC['op'], $handlers) ? $_GPC['op'] : '';
$staffModel = new staff();

if($operation == 'addnew'){
	if(!empty($_W['openid'])){


	$new['openid'] = $_W['openid'];
	$new['activity_id'] = $_GPC['activity'];
	// $new['qr'] = $staffModel->mk_qr($_W['openid']);
	
	//将地推人员基本信息（openid,地推活动ID），录入数据库
	$exists = $staffModel->exists($new);
	if(!$exists){
		$res = $staffModel->add_new($new);
		if($res){
			//为地推人员创建专属推广二维码
			$staffModel->mk_qr(pdo_insertid());
		}
	}
}
}

include $this->template('addnew');