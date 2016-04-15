<?php
/**
 * 微美发模块处理程序
 *
 * @author 
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class bm_meifaModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
    	$rid = $this->rule;
    	$sql = "SELECT * FROM " . tablename('meifa_reply') . " WHERE `rid`=:rid LIMIT 1";
    	$row = pdo_fetch($sql, array(':rid' => $rid));
    	if (empty($row['id'])) {
    		return $this->respText("请维护部门信息") ;
    	}
    	return $this->respNews(array(
				'Title' => $row['title'],
				'Description' => $row['order_info'],
				'PicUrl' => $_W['attachurl'] . $row['picurl'],
				'Url' => $this->createMobileUrl('index',array('id' => $row['id'])),
		));
	}
}