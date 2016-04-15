<?php
	$theone = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_templatenews')." WHERE  weid = :weid" , array(':weid' => $_W['uniacid']));
	if (checksubmit('submit')) {
		$insert = array(
			'weid' => $_W['uniacid'],
			'template_id' => trim($_GPC['template_id']),
			'sendGoodsSend' => trim($_GPC['sendGoodsSend']),
			'sendCommWarm' => trim($_GPC['sendCommWarm']),
			'sendCheckChange' => trim($_GPC['sendCheckChange']),
			'sendApplyMoneyBack' => trim($_GPC['sendApplyMoneyBack']),
			'sendMoneyBack' => trim($_GPC['sendMoneyBack']),
			'createtime' => TIMESTAMP
		);
		if(empty($theone)) {
			pdo_insert('hc_hunxiao_templatenews', $insert);
			!pdo_insertid() ? message('保存失败, 请稍后重试.','error') : '';
		} else {
			if(pdo_update('hc_hunxiao_templatenews', $insert,array('id' => $theone['id'])) === false){
				message('更新失败, 请稍后重试.','error');
			}
		}
		message('更新成功！', $this->createWebUrl('templatenews'), 'success');
	}
	include $this->template('template_news');
?>