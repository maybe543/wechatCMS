<?php
/**
 * 犀牛溜冰场模块订阅器
 *
 * @author Edward Gao <edward@weini.me>
 * @copyright 2014-2015 WeiNi Tech
 * @license MIT
 * @todo 清理代码，完善功能
 */
defined('IN_IA') or exit('Access Denied');

class Weini_rhinoModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微动力文档来编写你的代码
	}
}