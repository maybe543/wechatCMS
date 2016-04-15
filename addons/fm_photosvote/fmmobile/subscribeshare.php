<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

			$tfrom = $_GPC['tfrom'];
			$vote = $_GPC['vote'];			
			$tid = $_GPC['tid'];
			
			if (!empty($tid)) {
				$tuser = pdo_fetch("SELECT * FROM ".tablename($this->table_users)." WHERE uniacid = :uniacid and uid = :uid and rid = :rid", array(':uniacid' => $uniacid,':uid' => $tid,':rid' => $rid));
				$tfrom_user = $tuser['from_user'];
			}else {
				$tfrom_user = $_GPC['tfrom_user'];
			}
				
			include $this->template('subscribeshare');
			