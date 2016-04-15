<?php
/**
 * 老马群聊红包模块处理程序
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('CSS', '../addons/n1ce_adred/template/style/css/');
define('JS', '../addons/n1ce_adred/template/style/js/');
define('IMG', '../addons/n1ce_adred/template/style/images/');
class N1ce_adredModuleSite extends WeModuleSite {
	public $table_reply = 'n1ce_adred_reply';

	public function doMobileindex() {
		global $_W, $_GPC;
		$uniacid=$_W['uniacid'];
		//这个操作被定义用来呈现 微站首页导航图标
		$id = intval($_GPC['id']);
		
		$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
		$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl('index', array('id' => $reply['id']));
		if (!empty($reply)) {
			include $this->template('index');
		}else{
			exit('参数错误');
		}
	}
}
