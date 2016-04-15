<?php
/**
 * 微信文章精选模块定义
 *
 * @author cyl
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Cyl_wxweizhangModule extends WeModule {
	
	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		load()->func('file');
		
		//点击模块设置时将调用此方法呈现模块设置页面，$settings 为模块设置参数, 结构为数组。这个参数系统针对不同公众账号独立保存。
		//在此呈现页面中自行处理post请求并保存设置参数（通过使用$this->saveSettings()来实现）
		if(checksubmit()) {
			$data = $_GPC['data'];
			$data['dsfgg'] = htmlspecialchars_decode($data['dsfgg']);
			
            mkdirs(MODULE_ROOT . '/cert');
            
            $r=true;
    
            if(!empty($_GPC['cert'])) {
 
                $ret = file_put_contents(MODULE_ROOT . '/cert/apiclient_cert.pem.' . $_W['uniacid'], trim($_GPC['cert']));
                $r = $r && $ret;
               
            }
            if(!empty($_GPC['key'])) {
                $ret = file_put_contents(MODULE_ROOT . '/cert/apiclient_key.pem.' . $_W['uniacid'], trim($_GPC['key']));
                $r = $r && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $ret = file_put_contents(MODULE_ROOT . '/cert/rootca.pem.' . $_W['uniacid'], trim($_GPC['ca']));
                $r = $r && $ret;
            }
            if(!$r) {
                message('证书保存失败, 请保证 /addons/cgt_qyhb/cert/ 目录可写');
            }
            
			//字段验证, 并获得正确的数据$dat
			if (!$this->saveSettings($data)) {
				message('保存信息失败','','error');   // 保存失败
			} else {
				message('保存信息成功','','success'); // 保存成功
			}
		}
		//这里来展示设置项表单
		load()->func('tpl');
		//这里来展示设置项表单
		
		include $this->template('setting');
	}

}