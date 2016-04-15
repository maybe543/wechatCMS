<?php

defined('IN_IA') or exit('Access Denied');

class Yg_hcityModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'yg_hcity_reply';
	public function respond() {
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				if ($reply['starttime'] > time()) {//检测时间是否开始
					return $this->respText("本次活动尚未开始,敬请期待！");
				}elseif ($reply['endtime'] < time() || $reply['status'] == 0) {//检测时间是否结束或者状态是否为结束
					return $this->respText("本次活动已经结束，请关注我们后续的活动！");
				}elseif ($reply['status'] == 2) {//检测状态是否暂停
					return $this->respText("本次活动暂停中");
				}else{//活动大状态正常 检测每天的时间是否正常
					$news = array();
					$news[] = array(
						'title' => $reply['title'],
						'description' =>$reply['description'],
						'picurl' =>tomedia($reply['thumb']),
						'url' => $this->createMobileUrl('index', array('id' => $reply['id'])),
					);
					return $this->respNews($news);
				}
				
			}
		}
		return null;
	}
}