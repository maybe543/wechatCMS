<?php

defined('IN_IA') or exit('Access Denied');
define('HK_ROOT', IA_ROOT . '/addons/hawk_surpermoney');

class Hawk_surpermoneyModule extends WeModule {

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
			load()->func('file');
			mkdirs(HK_ROOT . '/cert');
			$r = true;
			if(!empty($_GPC['cert'])) {
				$ret = file_put_contents(HK_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
				$r = $r && $ret;
			}
			if(!empty($_GPC['key'])) {
				$ret = file_put_contents(HK_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
				$r = $r && $ret;
			}
			if(!empty($_GPC['ca'])) {
				$ret = file_put_contents(HK_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
				$r = $r && $ret;
			}
			if(!$r) {
				message('证书保存失败, 请保证 /addons/hawk_answer/cert/ 目录可写');
			}
			$input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip','learn','follow','low','type'), $_GPC);
			$input['appid'] = trim($input['appid']);
			$input['secret'] = trim($input['secret']);
			$input['mchid'] = trim($input['mchid']);
			$input['password'] = trim($input['password']);
			$input['ip'] = trim($input['ip']);
			$input['learn'] = trim($input['learn']);
			$input['follow'] = trim($input['follow']);
			$input['low'] = trim($input['low']);
			$input['type'] = trim($input['type']);
			$setting = $this->module['config'];
			$setting['api'] = $input;
			if($this->saveSettings($setting)) {
				message('保存参数成功', 'refresh');
			}
		}
		$config = $this->module['config']['api'];
		if(empty($config['ip'])) {
			$config['ip'] = $_SERVER['SERVER_ADDR'];
		}
		load()->func('tpl');
		//菜单列表页链接地址
		$listurl = $this->createMobileUrl('list');
		$listurl = substr($listurl,2,strlen($listurl));
		$listurl = "http://".$_SERVER['HTTP_HOST'].'/app/'.$listurl;
		//菜单用户中心链接地址
		$userurl = $this->createMobileUrl('user');
		$userurl = substr($userurl,2,strlen($userurl));
		$userurl = "http://".$_SERVER['HTTP_HOST'].'/app/'.$userurl;
		include $this->template('setting');
	}

}
