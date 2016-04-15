<?php

defined('IN_IA') or exit('Access Denied');

class Jing_guanzhuModule extends WeModule {

	public function settingsDisplay($settings) {
        global $_GPC, $_W;
        
        if (checksubmit()) {
            $cfg = array(
                'share_title' => $_GPC['share_title'],
                'share_content' => $_GPC['share_content'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		include $this->template('setting');
    }

}