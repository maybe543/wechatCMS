<?php

defined('IN_IA') or exit('Access Denied');
define('HK_ROOT', IA_ROOT . '/addons/hawk_ticket');

class Hawk_ticketModule extends WeModule {

	public function fieldsFormDisplay($rid = 0) {

	}

	public function fieldsFormValidate($rid = 0) {
		return '';
	}

	public function fieldsFormSubmit($rid = 0) {
		return true;
	}

	public function ruleDeleted($rid = 0) {
	}

	public function settingsDisplay($settings) {
		global $_GPC, $_W;
		if(checksubmit()) {
			$setting = $this->module['config'];
			$setting['follow'] = $_GPC['follow'];
			$setting['template'] = $_GPC['template'];
			$setting['templateid'] = $_GPC['templateid'];
			$setting['templateidhx'] = $_GPC['templateidhx'];
			if($this->saveSettings($setting)) {
				message('保存参数成功', 'refresh');
			}
		}
		$config = $this->module['config'];
		load()->func('tpl');
		include $this->template('setting');
	}

}
