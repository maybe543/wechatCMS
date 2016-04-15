<?php
/**
 * 虚拟来电模块微站定义
 *
 * @author  fm453 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class fm453_telModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
		if(checksubmit()) {
			$cfg = array(
                'title' => $_GPC['title'],
                's_botton' => $_GPC['s_botton'],
                'pic' => $_GPC['pic'],
                's_url' => $_GPC['s_url'],
				'm_url' => $_GPC['m_url'],
				'desc' => $_GPC['desc'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
		}
		if (empty($settings['title'])) {
            $settings['title'] = '你有一通来自方少的的未接电话！';
        }
		if (empty($settings['s_url'])) {
            $settings['s_url'] = 'http://mp.weixin.qq.com/s?__biz=MzA5NDE2MjQxOA==&mid=203036929&idx=1&sn=fcc888d8d517a5f68f76c26c2ab5dfdb#rd';
        }
		if (empty($settings['m_url'])) {
            $settings['m_url'] = $_W['siteroot'].'addons/fm453_tel/template/mobile/images/aini.mp3';
        }
        if (empty($settings['s_botton'])) {
            $settings['s_botton'] = '方少';
        }
        if (empty($settings['pic'])) {
            $settings['pic'] = $_W['siteroot'].'addons/fm453_tel/template/mobile/images/answer.png';
        }
		include $this->template('setting');
	}
}