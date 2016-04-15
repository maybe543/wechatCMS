<?php
defined('IN_IA') or exit('Access Denied');

class Xkd_fkzModule extends WeModule {

public function settingsDisplay($settings) {

		global $_W, $_GPC;
		
		load()->func('tpl');
		
		if($_W['ispost']) {
			
			$dat = $_GPC['dat'];
			
			if (!$this->saveSettings($dat)) {
				message('保存信息失败','','error');
			} else {
				message('保存信息成功','','success');
			}
		}
		
		if (empty($settings)) {
			$settings = array(
				'level' => 6,
				'num' => 3,
				'leaderlevel' => 5,
				'leadercondition' => 10,
				'enableleader' => 0,
				'level_money1' => 10,
				'level_money2' => 20,
				'level_money3' => 40,
				'level_money4' => 60,
				'level_money5' => 80,
				'level_money6' => 100,
				'level_money7' => 0,
				'level_money8' => 0,
				'level_money9' => 0,
				'level_money10' => 0,
				'level_money11' => 0,
				'level_money12' => 0,
			);
		}
		
		load()->func('tpl');
		
		include $this->template('setting');
	}

}