<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台管理员设置
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
if ($op == 'list') {
	$uniacid = intval($_W['uniacid']);
	$account = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :uniacid", array(':uniacid' => $uniacid));
	if (empty($account)) {
		message('抱歉，您操作的公众号不存在或是已经被删除！');
	}
	$permission = pdo_fetchall("SELECT u.uid,x.regionid as regionid,p.title,x.id as id FROM ".tablename('uni_account_users')."as u left join (".tablename('xcommunity_users') ."as x left join ".tablename('xcommunity_property')."as p on x.companyid = p.id ) on u.uid = x.uid WHERE x.uniacid = '{$uniacid}' AND role='operator'", array(), 'uid');

	if (!empty($permission)) {
		$member = pdo_fetchall("SELECT username, uid,status FROM ".tablename('users')."WHERE uid IN (".implode(',', array_keys($permission)).")", array(), 'uid');
		foreach ($permission as $key => $value) {
			$region = pdo_fetch("SELECT * FROM ".tablename('xcommunity_region')."WHERE id='{$value['regionid']}'") ;
			$permission[$key]['region_title'] = $region['title'];
		}
	}
	$uids = array();
	foreach ($permission as $v) {
		$uids[] = $v['uid'];
	}
	include $this->template('web/users/list');
}elseif ($op == 'add') {
	$uid = intval($_GPC['uid']);

	if ($uid) {
		$user = pdo_fetch("SELECT * FROM".tablename('users')."as u left join ".tablename('xcommunity_users')." as x on u.uid = x.uid WHERE u.uid=:uid",array(':uid' => $uid));
		$companies = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE pid=:companyid",array(":companyid" => $user['companyid']));
	}
	$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_property')."WHERE weid=:uniacid",array(':uniacid' => $_W['uniacid']));
	if (empty($list)) {
		// message('还没有添加物业',referer(),'error');exit();
		echo '没有添加物业';exit();
	}
	if(checksubmit()) {
		load()->model('user');
		$member = array();
		if (!$uid) {
			$member['username'] = trim($_GPC['username']);
		
			if(!preg_match(REGULAR_USERNAME, $member['username'])) {
				message('必须输入用户名，格式为 3-15 位字符，可以包括汉字、字母（不区分大小写）、数字、下划线和句点。');
			}
			if(user_check(array('username' => $member['username']))) {
				message('非常抱歉，此用户名已经被注册，你需要更换注册名称！');
			}
		}
		if (!empty($_GPC['password'])) {
			$member['password'] = $_GPC['password'];
		}
		if(istrlen($member['password']) < 8) {
			message('必须输入密码，且密码长度不得低于8位。');
		}
		$member['remark'] = $_GPC['remark'];
		//$member['groupid'] = intval($_GPC['groupid']) ? intval($_GPC['groupid']) : message('请选择所属用户组');
		$member['groupid'] = 1;
		$data = array(
					'uniacid' => $_W['uniacid'],
					'companyid' => intval($_GPC['companyid']),
					'regionid' => intval($_GPC['regionid']),
				);
		if ($uid) {
			$member['uid'] = $uid;
			$member['salt'] = $user['salt'];
			user_update($member);
			$data['uid'] = $uid;
			pdo_update('xcommunity_users',$data,array('uid' => $uid));
			message('保存成功！', referer(),'success');
		}else{
			$uid = user_register($member);
			$data['uid'] = $uid;
			pdo_insert('xcommunity_users',$data);
			$user = array(
					'uniacid' => $_W['uniacid'],
					'uid' => $uid,
					'role' => 'operator',
				);
			pdo_insert('uni_account_users',$user);	
			message('用户增加成功！', referer(),'success');
		}
		
		if($uid > 0) {
			unset($member['password']);
			
		}
		message('增加用户失败，请稍候重试或联系网站管理员解决！');
	}
	include $this->template('web/users/add');
}elseif($op == 'menu'){
	$menus = $this->NavMenu();
	// print_r($menus);exit();
	$id = intval($_GPC['id']);
	if ($id) {
		$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_users')."WHERE id=:id ",array(':id' => $id));
		$mmenus = explode(',', $item['menus']);
	}
	if (checksubmit('submit')) {
		$data = array(
				'menus' => is_array($_GPC['menus']) ? implode(',', $_GPC['menus']) : ''
			);
		if ($id) {
			$r = pdo_update('xcommunity_users',$data,array('id' => $id));
			if ($r) {
				$result = pdo_fetch("SELECT * FROM".tablename('users_permission')."WHERE uid=:uid",array(':uid' => $item['uid']));
				if (empty($result)) {
					$url = "c=home&a=welcome&do=ext&m=xfeng_community";
					pdo_insert('users_permission', array(
						'uid' => $item['uid'],
						'uniacid' => $_W['uniacid'],
						'url' => $url,
						'type' => 'xfeng_community',
						'permission' => 'all'
					));
				}
				
				message('权限修改成功',$this->createWebUrl('users',array('op' => 'list')),'success');
			}
		}
	}
	include $this->template('web/users/menu');
}elseif($op == 'set'){
	$uid = intval($_GPC['uid']);
	if (empty($uid)) {
		message('缺少参数',referer(),'error');
	}
	$type = $_GPC['type'];
	$data = intval($_GPC['data']);
	$data = ($data==1? 2:1);
	pdo_query("UPDATE ".tablename('users')."SET status = '{$data}' WHERE uid=:uid",array(":uid" => $uid ));
	die(json_encode(array("result" => 1, "data" => $data)));
}elseif ($op == 'delete') {
	$uid = intval($_GPC['uid']);
	if (empty($uid)) {
		message('缺少参数',referer(),'error');
	}
	$user = pdo_fetch("SELECT uid FROM".tablename('users')."WHERE uid=:uid",array(":uid" => $uid));
	if (empty($user)) {
		message('该用户不存在或已被删除',referer(),'error');
	}
	if (pdo_delete("users",array("uid" => $uid)) && pdo_delete("xcommunity_users",array("uid" => $uid)) && pdo_delete("uni_account_users",array("uid" => $uid))) {
		message("删除成功",referer(),'success');
	}
}elseif ($op == 'ajax') {
	if ($_GPC['companyid']) {
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE pid='{$_GPC['companyid']}'");
		print_r(json_encode($regions));exit();
	}
}elseif ($op == 'commission') {
	$id = intval($_GPC['id']);
	if ($id) {
		$user = pdo_fetch("SELECT * FROM".tablename('users')."as u left join ".tablename('xcommunity_users')." as x on u.uid = x.uid WHERE x.id=:id",array(':id' => $id));
	}
	if (checksubmit('submit')) {
		if ($id) {
			$r = pdo_update('xcommunity_users',array('commission' => $_GPC['commission']),array('id' => $id));
			if ($r) {
				message('设置成功',$this->createWebUrl('users',array('op' => 'list')),'success');
			}
			
		}
	}
	include $this->template('web/users/commission');
}






