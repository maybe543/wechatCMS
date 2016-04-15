<?php
/**
 * 女神来了模块定义
 *
 */
defined('IN_IA') or exit('Access Denied');
			
	if (checksubmit('submit')) {
		if (!empty($_GPC['title'])) {
			foreach ($_GPC['title'] as $index => $row) {
				$data = array(
					'title' => $_GPC['title'][$index],
					'rid' => $rid,
					'createtime' => time(),
				);
				if(!empty($data['title'])) {
					if(pdo_fetch("SELECT id FROM ".tablename($this->table_tags)." WHERE title = :title AND id != :id", array(':title' => $data['title'], ':id' => $index,':rid' => $rid))) {
						continue;
					}
					
					$row = pdo_fetch("SELECT id FROM ".tablename($this->table_tags)." WHERE title = :title  AND rid = :rid  LIMIT 1",array(':title' => $data['title'],':rid' => $rid));
					if(empty($row)) {
						pdo_update($this->table_tags, $data, array('id' => $index));
					}
					unset($row);
				}
			}
		}		
		if (!empty($_GPC['title-new'])) {
			foreach ($_GPC['title-new'] as $index => $row) {
				$data = array(
						'uniacid' => $uniacid,
						'rid' => $rid,
						'title' => $_GPC['title-new'][$index],
						'createtime' => time(),
				);
				if(!empty($data['title'])) {
					if(pdo_fetch("SELECT id FROM ".tablename($this->table_tags)." WHERE title = :title", array(':title' => $data['title'],':rid' => $rid))) {
						continue;
					}
					pdo_insert($this->table_tags, $data);
					unset($row);
				}
			}
		}
		
		if (!empty($_GPC['delete'])) {
			pdo_query("DELETE FROM ".tablename($this->table_tags)." WHERE id IN (".implode(',', $_GPC['delete']).")");
		}
		message('更新成功！', referer(), 'success');
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename($this->table_tags)." WHERE uniacid = :uniacid AND rid = :rid ORDER BY id DESC", array(':uniacid' => $uniacid, ':rid' => $rid));
		
	include $this->template('tags');
