<?php
/**
 * 捷讯活动平台模块订阅器
 *
 * @author 捷讯设计
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class J_activityModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看捷讯文档来编写你的代码
	}
}