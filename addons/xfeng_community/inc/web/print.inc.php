<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台打印机设置
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
$id = intval($_GPC['id']);
if ($op == 'add') {
	if ($user) {
		//物业管理员
		if (!$user['regionid']) {
			$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

		}
	}else{
		$regions = $this->regions();	
	}
	if ($id) {
		$settings = pdo_fetch("SELECT * FROM".tablename('xcommunity_print')."WHERE id=:id",array(':id' => $id));
		$regs = iunserializer($item['regionid']);
	}
	if (checksubmit('submit')) {
		$data = array(
			'uniacid'     => $_W['uniacid'],
			'print_type'  => intval($_GPC['print_type']),
			'member_code' => $_GPC['member_code'],
			'api_key'     => $_GPC['api_key'],
			'deviceNo'    => $_GPC['deviceNo'],
			'print_status' => intval($_GPC['print_status']),
		);
		if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
		if (empty($id)) {
			pdo_insert('xcommunity_print',$data);
		}else{
			pdo_update('xcommunity_print',$data,array('id' => $id));
		}
		message('提交成功',referer(),'success');
	}
	include $this->template('web/print/add');
}elseif ($op == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 20;
	$condition = '';
		if ($user) {
			$condition .=" AND uid='{$_W['uid']}'";
		}
	$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_print')."WHERE uniacid = :uniacid $condition LIMIT ".($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
	$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_print')."WHERE uniacid = :uniacid $condition ",array(':uniacid' => $_W['uniacid']));
	$pager  = pagination($total, $pindex, $psize);
	include $this->template('web/print/list');
}elseif($op == 'delete'){
		if (pdo_delete('xcommunity_print',array('id' => $id))) {
			$result = array(
					'status' => 1,
				);
			echo json_encode($result);exit();
		}
	}
