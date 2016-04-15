<?php
/**
 * 验证码红包模块处理程序
 *
 * @author pzh
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Yzmhb_pzhModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
	}
}