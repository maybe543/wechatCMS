<?php
/**
 * 捷讯活动平台模块处理程序
 *
 * @author 捷讯设计
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
class qw_cjhdptModuleProcessor extends WeModuleProcessor {
	public function respond() {
		global $_W;
		//$content = $this->message['content'];
		$rid = $this->rule;
		$sql = "SELECT * FROM " . tablename('qw_cjhdpt_reply') . " WHERE `rid`=:rid LIMIT 1";
		$row = pdo_fetch($sql, array(':rid' => $rid));
		if (empty($row['id'])) {
			return array();
		}
		$title = pdo_fetchcolumn("SELECT name FROM ".tablename('rule')." WHERE id = :rid LIMIT 1", array(':rid' => $rid));
		return $this->respNews(array(
			'Title' => $title,
			'Description' => $row['description'],
			'PicUrl' => $_W['attachurl'] . $row['picture'],
			'Url' => './index.php?c=entry&m=qw_cjhdpt&id='.$row['id'].'&do=view&i='.$_W['uniacid']."&wxref=mp.weixin.qq.com#wechat_redirect",
		));
	}
}