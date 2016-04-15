<?php
/**
 * 
 *
 * @author desmond
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Ditui_qrModuleSite extends WeModuleSite {
	public function doMobileGateway(){
		global $_W;
		include $this->template('index');
	}
	

}