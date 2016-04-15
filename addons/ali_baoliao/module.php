<?php
/**
 * 报料台模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');

class Ali_BaoliaoModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit('submit')) {
			//字段验证, 并获得正确的数据$dat
			$dat['option1'] = $_GPC['option1'];
			$dat['option2'] = $_GPC['option2'];
			$dat['option3'] = $_GPC['option3'];
			$dat['option4'] = $_GPC['option4'];
			$this->saveSettings($dat);
			message('配置参数更新成功！', referer(), 'success');
		}
		//这里来展示设置项表单
		include $this->template('settings');
	}

}