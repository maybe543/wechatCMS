<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if (!empty($rid)) {
			pdo_delete($this->table_log, " rid = ".$rid);
			$users = pdo_fetchall("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and rid = :rid", array(':uniacid' => $uniacid,':rid' => $rid));
			foreach ($$users as $key => $value) {
				pdo_update($this->table_users, array('photosnum' => 0, 'xnphotosnum' => 0, 'hits' => 0, 'xnhits' => 0, 'yaoqingnum' => 0, 'sharenum' => 0, 'zans' => 0), array('from_user' => $value['from_user'], 'rid' => $rid, 'uniacid' => $uniacid));
			}
			message('删除成功！', referer(),'success');
		}		
	