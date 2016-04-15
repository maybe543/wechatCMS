<?php
/**
 * 翻红包模块订阅器
 *
 * @author 乐不思蜀
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Enjoy_redModuleReceiver extends WeModuleReceiver {
	public function receive() {
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微赞文档来编写你的代码
		$type = $this->message['type'];
		if($this->message['event'] == 'unsubscribe') {
			pdo_update('enjoy_red_fans', array(
			'subscribe' => 0,
			'subscribe_time' => '',
			), array('openid' => $this->message['fromusername'],'uniacid' => $GLOBALS['_W']['uniacid']));
		}elseif($this->message['event'] == 'subscribe') {
			pdo_update('enjoy_red_fans', array(
			'subscribe' => 1,
			'subscribe_time' => time(),
			), array('openid' => $this->message['fromusername'],'uniacid' => $GLOBALS['_W']['uniacid']));
				
		}
	}
}