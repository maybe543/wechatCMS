<?php
	$id = intval($_GPC['id']);
	if(checksubmit('submit')) {
		if (!empty($_GPC['template'])) {
			pdo_update('wr_printer', array('template' => $_GPC['template']), array('rid' => $id));
			message('选择模板成功！', $this->createWebUrl('template', array('id' => $id)), 'success');
		}else{
			message('选择模板失败！', $this->createWebUrl('template', array('id' => $id)), 'error');
		}
	}
	$template = pdo_fetchcolumn("SELECT template FROM " . tablename('wr_printer'). " WHERE rid = '{$id}'");
	include $this->template('template');
?>