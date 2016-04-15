<?php
/**
 * 虚拟来电模块微站定义
 *
 * @author  fm453 
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('RES', '../addons/fm453_tel/template/mobile/');
class fm453_telModuleSite extends WeModuleSite {

	public function doMobiletel() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$title = isset($this->module['config']['title']) ? $this->module['config']['title'] : '您有一通来自方少的未接电话！';
		$desc = isset($this->module['config']['desc']) ?$this->module['config']['desc'] : '';
		$pic = isset($this->module['config']['pic']) ? $this->module['config']['pic'] : $_W['siteroot'].'addons/fm453_tel/template/mobile/images/answer.png';
		$pic = tomedia($pic);
		$s_botton = isset($this->module['config']['s_botton']) ? $this->module['config']['s_botton'] : '方少';
		$s_url = isset($this->module['config']['s_url']) ? $this->module['config']['s_url'] : '';
		$m_url = isset($this->module['config']['m_url']) ? $this->module['config']['m_url'] : $_W['siteroot'].'addons/fm453_tel/template/mobile/images/aini.mp3';
		$pageurl = $_W['siteroot'].'app/'.$this->createMobileUrl('tel');
		$ontelurl = $_W['siteroot'].'app/'.$this->createMobileUrl('ontel');
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			include $this->template('no_sub');
			exit();
		}
		include $this->template('tel');
	}

	public function doMobileontel() {
		global $_W,$_GPC;
		$title = isset($this->module['config']['title']) ? $this->module['config']['title'] : '你有一通来自方少的未接电话！';
		$desc = isset($this->module['config']['desc']) ?$this->module['config']['desc'] : '';
		$pic = isset($this->module['config']['pic']) ? $this->module['config']['pic'] : $_W['siteroot'].'addons/fm453_tel/template/mobile/images/answer.png';
		$pic = tomedia($pic);
		$pageurl = $_W['siteroot'].'app/'.$this->createMobileUrl('tel');
		$s_url = isset($this->module['config']['s_url']) ? $this->module['config']['s_url'] : '';
		$s_botton = isset($this->module['config']['s_botton']) ? $this->module['config']['s_botton'] : '方少';
		$m_url = isset($this->module['config']['m_url']) ? $this->module['config']['m_url'] : $_W['siteroot'].'addons/fm453_tel/template/mobile/images/aini.mp3';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
			include $this->template('no_sub');
			exit();
		}
		include $this->template('ontel');
	}

}