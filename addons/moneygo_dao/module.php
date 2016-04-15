<?php
/**
 * 夺宝岛模块定义
 *
 * 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Moneygo_daoModule extends WeModule {
	public function settingsDisplay($settings) {
		// 声明为全局才可以访问到.
		global $_W, $_GPC;
		if(checksubmit()) {
			// $_GPC 可以用来获取 Cookies,表单中以及地址栏参数
			$dat = $_GPC['dat'];
			// message() 方法用于提示用户操作提示
			empty($dat['jishu'])&& message('请填写夺宝基数');
			empty($dat['share_title']) && message('请填写分享标题');
			empty($dat['share_image']) && message('请填写分享图片');
			empty($dat['share_desc']) && message('请填写分享描述');
			$dat['int_desc'] = htmlspecialchars_decode($dat['int_desc']);
			//字段验证, 并获得正确的数据$dat
			if (!$this->saveSettings($dat)) {
				message('保存信息失败','','error');
			} else {
				$share['uniacid']=$_W['uniacid'];
				$share['share_title']=$dat['share_title'];
				$share['share_image']=$dat['share_image'];
				$share['share_desc']=$dat['share_desc'];
				$share['appid']=$_W['account']['key'];
				$share['appsecret']=$_W['account']['secret'];
				$share['win_mess']=$dat['win_mess'];
				$share['jishu'] = $dat['jishu'];
				$share['index_bg'] = $dat['index_bg'];
				$share['index_ban'] = $dat['index_ban'];

				$uniacid=$_W['uniacid'];
				$list = pdo_fetch("SELECT * FROM ".tablename('moneygo_wechat')." WHERE uniacid = '{$uniacid}'");
				if (empty($list['uniacid'])) {
					$ret = pdo_insert('moneygo_wechat', $share);
				}else{
					$ret = pdo_update('moneygo_wechat', $share, array('uniacid'=>$_W['uniacid']));
				}
				message('保存信息成功','','success');
			}
		}
		
		// 模板中需要用到 "tpl" 表单控件函数的话, 记得一定要调用此方法.
		load()->func('tpl');
		
		//这里来展示设置项表单
		include $this->template('setting');
	}

}