<?php
	global $_GPC, $_W;
		//checklogin();
		$id = intval($_GPC['id']);
		if (checksubmit('delete') && !empty($_GPC['select'])) {
			$replies = pdo_fetchall("SELECT id,pic FROM ".tablename('wr_printer_pic')." WHERE id  IN  ('".implode("','", $_GPC['select'])."')");
			if (!empty($replies)) {
				foreach ($replies as $index => $row) {
					file_delete($row['pic']);
				}
			}
			pdo_delete($this->tablename, " id  IN  ('".implode("','", $_GPC['select'])."')");
			message('删除成功！', $this->createWebUrl('pic', array('id' => $id, 'page' => $_GPC['page'])));
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$list = pdo_fetchall("SELECT a.id,a.rid,a.pic,a.msg,a.create_time,a.bianhao,a.fid,b.nickname FROM ".tablename('wr_printer_pic')." AS a INNER JOIN ".tablename('mc_members')." AS b ON a.fid=b.uid WHERE a.rid = '{$id}' ORDER BY a.create_time DESC LIMIT ".($pindex - 1) * $psize.",{$psize}");
		if (!empty($list)) {
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wr_printer_pic') . " WHERE rid = '{$id}'");
			$total1 = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('wr_printer_pic') . " WHERE rid = '{$id}' and create_time > '".strtotime(date('Y-m-d'))."'");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('pic');
?>