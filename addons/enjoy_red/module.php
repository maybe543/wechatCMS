<?php
/**
 * 翻红包模块定义
 *
 * @author 乐不思蜀
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/enjoy_red');
class Enjoy_redModule extends WeModule {
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
	}

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		global $_W, $_GPC;
		if(checksubmit()) {
			load()->func('file');
			mkdirs(MB_ROOT . '/cert');
			$r = true;
			if(!empty($_GPC['cert'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
				$r = $r && $ret;
			}
			if(!empty($_GPC['key'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
				$r = $r && $ret;
			}
			if(!empty($_GPC['ca'])) {
				$ret = file_put_contents(MB_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
				$r = $r && $ret;
			}
			if(!$r) {
				message('证书保存失败, 请保证 /addons/enjoy_red/cert/ 目录可写');
			}
			$input = array_elements(array('appid', 'secret', 'mchid', 'password', 'ip','mid'), $_GPC);
			$input['appid'] = trim($input['appid']);
			$input['secret'] = trim($input['secret']);
			$input['mchid'] = trim($input['mchid']);
			$input['mid'] = trim($input['mid']);
			$input['password'] = trim($input['password']);
			$input['ip'] = trim($input['ip']);
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
		//这里来展示设置项表单
		include $this->template('setting');
	}

}