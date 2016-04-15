<?php

defined('IN_IA') or exit('Access Denied');

class Amouse_friend_impressModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
        load()->func('tpl');
        if (checksubmit()) {
            $dat = array(
                'followurl' => trim($_GPC['followurl']),
                'appid' => trim($_GPC['appid']),
                'secret' => trim($_GPC['secret']),
                'enable'=> trim($_GPC['enable']),
                'gzDwz'=> trim($_GPC['gzDwz']),
                'weixinid' => trim($_GPC['weixinid']),
                'qrcode_thumb'=>trim($_GPC['qrcode_thumb']),
            );
            if ($this->saveSettings($dat)) {
                message('保存成功', 'refresh');
            }
        }
		include $this->template('setting');
	}

}