<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区常用查询
 */
	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op'])?$_GPC['op']:'list';
	$id = intval($_GPC['id']);
	//判断是否是操作员
	$user = $this->user();
	if ($op == 'add') {
			//查所有小区信息
		if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
			if($id){
				$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_search')."WHERE id=:id",array(':id' => $id));
				if (empty($item)) {
					message('信息不存在或已被删除',referer(),'error');
				}
				$regs = unserialize($item['regionid']);
			}
			$data = array(
				'weid'  => $_W['weid'],
				'sname' => $_GPC['sname'],
				'surl'  => $_GPC['surl'],
				'icon'  => $_GPC['icon'],
				'status' => $_GPC['status'],
				'regionid' => $regionid,
			);
			if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
			if(checksubmit('submit')){
				if (empty($_GPC['id'])) {
					pdo_insert("xcommunity_search",$data);
				}else {
					pdo_update("xcommunity_search",$data,array('id' => $id));
				}
				message('更新成功',referer(),'success');
			}
			load()->func('tpl');
			include $this->template('web/search/add');
	}elseif($op == 'list'){
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		if ($user) {
			$condition .=" AND uid='{$_W['uid']}'";
		}
		$sql = "SELECT * FROM".tablename('xcommunity_search')."WHERE weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list   = pdo_fetchall($sql);
		// if ($user) {
		// 	$list1   = pdo_fetchall($sql);
		// 	foreach ($list1 as $key => $value) {
		// 		$regionids = unserialize($value['regionid']);
		// 		if (is_array($regionids)) {
		// 			if (@in_array($user['regionid'],$regionids)) {
		// 				$list[$key]['sname'] = $value['sname']; 
		// 				$list[$key]['surl'] = $value['surl'];
		// 				$list[$key]['status'] = $value['status'];
		// 				$list[$key]['id'] = $value['id'];	
		// 			}
		// 		}else{
		// 			if ($regionids == $user['regionid']) {
		// 				$list[$key]['sname'] = $value['sname']; 
		// 				$list[$key]['surl'] = $value['surl'];
		// 				$list[$key]['status'] = $value['status'];
		// 				$list[$key]['id'] = $value['id'];				
		// 			}
		// 		}
				
		// 	}
		// }else{
		// 	$list   = pdo_fetchall($sql);
		// }
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_search')."WHERE weid='{$_W['weid']}'");
		$pager  = pagination($total, $pindex, $psize);	
		include $this->template('web/search/list');
	}elseif($op == 'delete'){
		pdo_delete("xcommunity_search",array('id' => $_GPC['id']));
		message('删除成功',referer(),'success');
	}elseif ($op == 'set') {
		$id = intval($_GPC['id']);
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);

		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_search", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}

