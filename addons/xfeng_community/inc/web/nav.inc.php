<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台菜单设置
 */
	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id = intval($_GPC['id']);
	$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
	if (empty($regions)) {
		message('请先添加小区',$this->createWebUrl('region',array('op' => 'add')),'error');
	}
	if ($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_nav')."WHERE  uniacid='{$_W['uniacid']}' AND pcate = 0 order by displayorder asc LIMIT ".($pindex - 1) * $psize.','.$psize);
		$children = array();
		foreach ($list as $key => $value) {
			$sql  = "select *from".tablename("xcommunity_nav")."where uniacid='{$_W['uniacid']}' and  pcate='{$value['id']}' order by displayorder asc";
			$li = pdo_fetchall($sql);

			$children[$value['id']] = $li;
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_nav')."WHERE uniacid='{$_W['uniacid']}' AND pcate = 0 ");
		$pager  = pagination($total, $pindex, $psize);

		// AJAX是否显示
		if($_W['isajax'] && $_GPC['id']){
			$data = array();
			$data['status'] = intval($_GPC['status']);
			if(pdo_update('xcommunity_nav', $data, array('id' => $_GPC['id'])) !== false) {
					exit('success');
			}
			
		}
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('xcommunity_nav', array('displayorder' => $displayorder), array('id' => $id));
			}
			message('排序更新成功！', 'refresh', 'success');
		}
		include $this->template('web/nav/list');
	}elseif ($op == 'add') {
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_nav')."WHERE id=:id",array(":id" => $id));
			$regs = unserialize($item['regionid']);
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['url'])) {
				$url = '#';
			}else{
				$url = $_GPC['url'];
			}
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'displayorder' => $_GPC['displayorder'],
				'title' => $_GPC['title'],
				'url' => $url, 
				'status' => 1,
				'icon' => $_GPC['icon'],
				'bgcolor' => $_GPC['bgcolor'],
				'thumb' => $_GPC['thumb'],
			);
			if (!empty($_GPC['regionid']) && $_GPC['regionid'] != 'N;') {
				$insert['regionid'] = serialize($_GPC['regionid']);
			}else{
				$insert['enable'] = 1;
			}
			if ($_GPC['id']) {
				$insert['pcate'] = $item['pcate'];
				pdo_query("UPDATE ".tablename('xcommunity_nav')."SET enable = 0 WHERE id='{$_GPC['id']}'");
				pdo_update('xcommunity_nav',$insert,array('id' => $_GPC['id']));
			}else{
				$insert['pcate'] = $_GPC['pcate'];
				pdo_insert('xcommunity_nav',$insert);
			}
			message('操作成功',referer(),'success');
		}
		load()->func('tpl');
		include $this->template('web/nav/add');
	}elseif ($op == 'cover') {
	//设置入口信息
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$nav = pdo_fetch("SELECT * FROM".tablename('xcommunity_nav')."WHERE id=:id AND uniacid=:uniacid",array(':uniacid' => $_W['uniacid'],':id' => $id));
		if (empty($nav)) {
			message('该菜单不存在或已删除',referer(),'error');
		}
		$reply = pdo_fetch("SELECT id,rid,title,thumb,description FROM".tablename('cover_reply')."WHERE do='{$nav['do']}' AND uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		if ($reply) {
			$k = pdo_fetchall("SELECT content FROM".tablename('rule_keyword')."WHERE rid=:rid",array(':rid' => $reply['rid']));
			$kds = '';
			foreach ($k as $key => $value) {
				$kds[]= $value['content'];
			}
			$kds = implode('|',$kds);
		}
		if (checksubmit('submit')) {
			if(trim($_GPC['keywords']) == '') {
				message('必须输入触发关键字.');
			}
			$keywords = explode('|',$_GPC['keywords']);
			$rule = array(
				'uniacid' => $_W['uniacid'],
				'name' => $_GPC['title'],
				'module' => 'cover', 
				'status' => 1,
			);
			if (!empty($reply)) {
				$rid = $reply['rid'];
				$result = pdo_update('rule',$rule,array('id' => $rid));
			}else{
				$result = pdo_insert('rule', $rule);
				$rid = pdo_insertid();
			}
			if (!empty($rid)) {
				$sql = 'DELETE FROM '. tablename('rule_keyword') . ' WHERE `rid`=:rid AND `uniacid`=:uniacid';
				$pars = array();
				$pars[':rid'] = $rid;
				$pars[':uniacid'] = $_W['uniacid'];
				pdo_query($sql, $pars);
				$rowtpl = array(
					'rid' => $rid,
					'uniacid' => $_W['uniacid'],
					'module' => 'cover',
					'status' => $rule['status'],
					'displayorder' => $rule['displayorder'],
				);
				foreach($keywords as $kw) {
					$krow = $rowtpl;
					$krow['type'] = 1;
					$krow['content'] = $kw;
					pdo_insert('rule_keyword', $krow);
				}
				$url = murl('entry', array('do' => $nav['do'], 'm' => 'xfeng_community'));
				$entry = array(
					'uniacid' => $_W['uniacid'],
					'multiid' => $multiid,
					'rid' => $rid,
					'title' => $_GPC['title'],
					'description' => $_GPC['description'],
					'thumb' => $_GPC['thumb'],
					'url' => $url,
					'do' => $nav['do'],
					'module' => 'xfeng_community',
				);
				if (empty($reply['id'])) {
					pdo_insert('cover_reply', $entry);
				} else {
					pdo_update('cover_reply', $entry, array('id' => $reply['id']));
				}
				message('封面保存成功！', 'refresh', 'success');
			} else {
				message('封面保存失败, 请联系网站管理员！');
			}
		}
		load()->func('tpl');
	include $this->template('web/nav/cover');
}elseif($op == 'delete'){
		if (pdo_delete('xcommunity_nav',array('id' => $id))) {
			$result = array(
					'status' => 1,
				);
			echo json_encode($result);exit();
		}
}elseif($op == 'set'){
	$id = intval($_GPC['id']);
	if (empty($id)) {
		message('缺少参数',referer(),'error');
	}
	$type = $_GPC['type'];
	$data = intval($_GPC['data']);
	$data = ($data==1? 0:1);
	pdo_query("UPDATE ".tablename('xcommunity_nav')."SET isshow = '{$data}' WHERE id=:id",array(":id" => $id ));
	die(json_encode(array("result" => 1, "data" => $data)));
}











