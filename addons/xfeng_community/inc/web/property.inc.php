<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台物业团队
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	if ($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE :keyword";
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		//判断是否是操作员
		$user = $this->user();
		if ($user['regionid']) {
			message('小区管理员没有管理物业的权限',referer(),'error');exit();
		}
		if ($user) {
			//$region = pdo_fetch("SELECT pid FROM".tablename('xcommunity_region')."WHERE id=:id",array(':id' => $user['regionid']));
			// $condition .="AND id=:id";
			// $params[':id'] = $region['pid'];
			$condition .=" AND id=:id";
			$params[':id'] = $user['companyid'];
		}
		$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_property')."WHERE weid='{$_W['uniacid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_property')."WHERE weid='{$_W['uniacid']}' $condition",$params);
		$pager  = pagination($total, $pindex, $psize);
		include $this->template('web/property/list');
	}elseif ($op == 'add') {
		$id = intval($_GPC['id']);
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=0");
		if (empty($id)) {
			if (empty($regions)) {
				message('已无绑定的小区，请先添加小区',$this->createWebUrl('region',array('op' => 'add')),'error');
			}
		}

		
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_property')."WHERE weid=:weid AND id=:id",array(":weid" => $_W['uniacid'],":id" => $id));
			if (empty($item)) {
				message('该信息不存在或已删除',referer(),'error');
			}
			$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
			$regs = iunserializer($item['regionid']);
		}
		if (checksubmit('submit')) {
			$regionid = $_GPC['regionid'];
			$data = array(
					'weid' => $_W['uniacid'],
					'title' => $_GPC['title'],
					'topPicture' => $_GPC['thumb'],
					'content' => htmlspecialchars_decode($_GPC['content']),
					'createtime' => TIMESTAMP,
					'regionid' => serialize($_GPC['regionid']),
					'telphone' => $_GPC['telphone'],
				);
			if ($id) {
				foreach (@$regs as $key => $value) {
					pdo_query("UPDATE ".tablename("xcommunity_region")."SET pid=0 WHERE id='{$value}'");
				}
				pdo_update('xcommunity_property',$data,array("id" => $id));
			}else{
				pdo_insert('xcommunity_property',$data);
				$id = pdo_insertid();
			}
			foreach ($regionid as $key => $value) {
				pdo_query("UPDATE ".tablename("xcommunity_region")."SET pid='{$id}' WHERE id='{$value}'");
			}	
			message('提交成功',referer(), 'success');
		}
		load()->func('tpl');
		include $this->template('web/property/add');
	}elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		if ($_W['isajax']) {
			if (empty($id)) {
			message('缺少参数',referer(),'error');
			}
			$item = pdo_fetch("SELECT id,topPicture FROM".tablename('xcommunity_property')."WHERE weid='{$_W['weid']}' AND id=:id",array(':id' => $id));
			if (empty($item)) {
				message('该物业不存在或已被删除',referer(),'error');
			}
			$r = pdo_delete("xcommunity_property",array('id' => $id));
			if ($r) {
				$result = array(
						'status' => 1,
					);
				echo json_encode($result);exit();
			}
		}
		// pdo_delete('xcommunity_property',array('id' => $id));
		// load()->func('file');
		// file_delete($item['topPicture']);
		// message('删除成功',referer(),'success');

	}elseif ($op == 'payment') {
		$pid = intval($_GPC['pid']);
		$id = intval($_GPC['id']);
		if (checksubmit('submit')) {
			$data = array(
					'uniacid' => $_W['uniacid'],
					'pid' => $pid,
					'account' => $_GPC['account'],
					'partner' => $_GPC['partner'],
					'secret' => $_GPC['secret'],
				);
			if ($id) {
				pdo_update('xcommunity_alipayment',$data,array('id' => $id));
			}else{
				pdo_insert('xcommunity_alipayment',$data);
			}
			message('提交成功',referer(),'success');
		}
		$condtion = ' uniacid =:uniacid';
		$params[':uniacid'] = $_W['uniacid'];
		
		$condtion .=" AND pid=:pid";
		$params[':pid'] = $pid;
		
		$payment = pdo_fetch("SELECT * FROM".tablename('xcommunity_alipayment')."WHERE $condtion",$params);
		include $this->template('web/property/payment');
	}
	











