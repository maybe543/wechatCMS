<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if (!empty($rid)) {
			pdo_delete($this->table_bbsreply, " rid = ".$rid);
			message('删除成功！', referer(),'success');
		}		
		