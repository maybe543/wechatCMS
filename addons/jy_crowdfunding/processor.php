
<?php
/**
 * 众筹项目模块处理程序
 *
 */
defined('IN_IA') or exit('Access Denied');

class Jy_crowdfundingModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微盒子文档来编写你的代码
	}
}
?>