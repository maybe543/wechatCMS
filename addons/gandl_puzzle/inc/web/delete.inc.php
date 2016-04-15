<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;


	$id = intval($_GPC['id']);
	if(empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}

	$puzzle = pdo_fetch("select * from " . tablename('gandl_puzzle') . " where uniacid=:uniacid and id=:id ", array(':uniacid' => $_W['uniacid'],':id' => $id));
	if(empty($puzzle)) {
		message('抱歉，没有相关数据！', '', 'error');
	}

	// 如果解密正在进行中，则不允许删除
	if($puzzle['start_time']<time() && $puzzle['end_time']>time()){
		message('该活动正在进行中，不能删除！', '', 'error');
	}

	if(false === pdo_delete('gandl_puzzle', array('uniacid' => $_W['uniacid'],'id' => $id),'AND')){
		message('删除失败，请重试', '', 'error');
	}

	message('删除成功！', $this->createWebUrl('list'), 'success');

?>