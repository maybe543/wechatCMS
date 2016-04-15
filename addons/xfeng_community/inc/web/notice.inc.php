<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区通知设置
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	
	//判断是否是操作员
	$user = $this->user();
	if ($op == 'list') {
		$condition = '';
		if ($user) {
			$condition .=" AND n.uid='{$_W['uid']}'";
		}

		$list = pdo_fetchall("SELECT n.*,m.realname as realname FROM".tablename('xcommunity_wechat_notice')."as n left join".tablename('xcommunity_member')."as m on n.fansopenid = m.openid WHERE n.uniacid = '{$_W['uniacid']}' $condition");
		// foreach ($list as $key => $value) {
		// 	load()->model('mc');
		// 	$member = mc_fetch($value['memberid'],array('realname'));
		// 	$list[$key]['realname'] = $member['realname'];
		// }
		include $this->template('web/notice/list');
	}elseif ($op == 'add') {
		if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
		// $condition = 'weid=:weid';
		// $parms[':weid'] = $_W['uniacid'];
		// if ($user) {
		// 	$condition .=" AND regionid=:regionid";
		// 	$parms[':regionid'] = $user['regionid'];
		// }
		// 	$condition = '';
		// if ($user) {
		// 		if ($user['regionid']) {
		// 			$condition .="AND m.regionid=:regionid";
		// 			$params[':regionid'] = $user['regionid'];
		// 		}else{
		// 			$condition .=" AND r.pid =:pid";
		// 			$params[':pid'] = $user['companyid'];
		// 		}
				
		// 	}
		// $sql    = "select m.*,r.title as title,r.city,r.dist from ".tablename("xcommunity_member")."as m left join".tablename('xcommunity_region')."as r on m.regionid = r.id where m.weid='{$_W['weid']}' $condition order by m.id desc  ";
		// $members   = pdo_fetchall($sql,$params);
		// if (empty($members)) {
		// 	message('该小区还没有用户,无法添加微信通知管理员.',referer(),'error');
		// }
		if (checksubmit('submit')) {
			$data = array(
					'uniacid' => $_W['uniacid'],
					'fansopenid' => $_GPC['fansopenid'],
					'repair_status' => $_GPC['repair_status'],
					'report_status' => $_GPC['report_status'],
					'shopping_status' => $_GPC['shopping_status'],
					'homemaking_status' => $_GPC['homemaking_status'],
					'type' => intval($_GPC['type']),
				);
			if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
			$notice = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE uniacid=:uniacid AND fansopenid=:fansopenid",array(':uniacid' => $_W['uniacid'],':fansopenid' => $data['fansopenid']));
			if ($notice) {
				message('该用户已经设置为管理员,无需再添加',referer(),'error');
			}
			if ($id) {
				if(pdo_update('xcommunity_wechat_notice',$data,array('id' => $id))){
					message('提交成功',referer(),'success');
				}
			}else{
				if(pdo_insert('xcommunity_wechat_notice',$data)){
					message('提交成功',referer(),'success');
				}
			}
		}
		include $this->template('web/notice/add');
	}elseif($op == 'delete'){
		$id = intval($_GPC['id']);
		if ($id) {
			$r = pdo_delete('xcommunity_wechat_notice',array('id' => $id));
			if ($r) {
				$result = array(
					'status' => 1,
				);
				echo json_encode($result);exit();
			}
			
		}
	}elseif($op == 'verify'){
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('repair_status','report_status','shopping_status','homemaking_status'))) {
			$data = ($data==2?'1':'2');
			pdo_update("xcommunity_wechat_notice", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
	}

