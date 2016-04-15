<?php
/**
 * 财务中心模块订阅器
 *
 * @author Kim 模块开发QQ:800083075
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';

class Kim_financialModuleReceiver extends WeModuleReceiver {
	public function receive() {
		$type = $this->message['type'];
		//这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微赞文档来编写你的代码
        //出发套餐回收
        common_group_check();
	}
}