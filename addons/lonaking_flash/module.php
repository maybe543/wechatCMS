<?php
defined('IN_IA') or exit('Access Denied');

class Lonaking_flashModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;

		include $this->template('setting');
	}

}