<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区电话信息
 */
	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$id = intval($_GPC['id']);
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	//判断是否是操作员
	$user = $this->user();
	if ($op == 'list') {
		//常用号码显示
		
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$condition = '';
		if ($user) {
			$condition .=" AND uid='{$_W['uid']}'";
		}
		$sql    = "select * from ".tablename("xcommunity_phone")."where weid = '{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list   = pdo_fetchall($sql);
		// if ($user) {
		// 	$list1   = pdo_fetchall($sql);
		// 	foreach ($list1 as $key => $value) {
		// 		$regionids = unserialize($value['regionid']);
		// 		if (is_array($regionids)) {
		// 			if (@in_array($user['regionid'],$regionids)) {
		// 				$list[$key]['content'] = $value['content'];
		// 				$list[$key]['phone'] = $value['phone'];
		// 				$list[$key]['thumb'] = $value['thumb'];
		// 				$list[$key]['id'] = $value['id'];	
		// 			}
		// 		}else{
		// 			if ($regionids == $user['regionid']) {
		// 				$list[$key]['content'] = $value['content'];
		// 				$list[$key]['phone'] = $value['phone'];
		// 				$list[$key]['thumb'] = $value['thumb'];
		// 				$list[$key]['id'] = $value['id'];					
		// 			}
		// 		}
				
		// 	}
		// }else{
		// 	$list   = pdo_fetchall($sql);
		// }
		$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_phone")."where  weid = '{$_W['weid']}' ");
		$pager  = pagination($total, $pindex, $psize);
		if (!empty($_GPC['displayorder'])) {
			foreach ($_GPC['displayorder'] as $id => $displayorder) {
				pdo_update('xcommunity_phone', array('displayorder' => $displayorder), array('id' => $id));
			}
			message('排序更新成功！', 'refresh', 'success');
		}

		include $this->template('web/phone/list');
	}elseif ($op == 'delete') {
		//常用号码删除
		$r = pdo_delete("xcommunity_phone",array('id'=>$id));
		if ($r) {
			message('删除成功',referer(),'success');
		}
	}elseif ($op == 'add') {
		//查所有小区信息
		if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_phone')."WHERE id=:id",array(':id' => $id));
			$regs = unserialize($item['regionid']);
		}
		if (checksubmit('submit')) {
			$data = array(
					'weid' => $_W['uniacid'],
					'phone' => $_GPC['phone'],
					'content' => $_GPC['content'],
					'displayorder' => intval($_GPC['displayorder']),
					'thumb' => $_GPC['thumb'],
				);
			if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
			if ($id) {
				pdo_update('xcommunity_phone',$data,array('id' => $id));
			}else{
				pdo_insert('xcommunity_phone',$data);
			}
			message('提交成功',referer(),'success');
		}
		load()->func('tpl');
		include $this->template('web/phone/add');
	}
	












