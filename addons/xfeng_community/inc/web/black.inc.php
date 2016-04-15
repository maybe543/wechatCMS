<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台黑名单管理
 */
global $_W,$_GPC;
$GLOBALS['frames'] = $this->NavMenu();
$op = !empty($_GPC['op'])?$_GPC['op']:'list';
$id = intval($_GPC['id']);
$type = !empty($_GPC['type']) ? $_GPC['type'] : 2;
if ($type == 2) {
		$table = 'xcommunity_fled';
}else{
	$table = 'xcommunity_carpool';
}
if ($op == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 10;
	$condition = '';
	//判断是否是操作员
	// $user = $this->user();
	// if ($user) {
	// 	$condition .="AND regionid=:regionid";
	// 	$params[':regionid'] = $user['regionid'];
	// }
	$user = $this->user();
			if ($user) {
				if ($user['regionid']) {
					$condtion .="AND f.regionid=:regionid";
					$params[':regionid'] = $user['regionid'];
				}else{
					$condition .=" AND r.pid =:pid";
					$params[':pid'] = $user['companyid'];
				}
				
			}
	$list = pdo_fetchall("SELECT f.*,r.title FROM".tablename($table)."as f left join ".tablename('xcommunity_region')."as r on f.regionid = r.id WHERE f.weid='{$_W['weid']}' AND f.black = 1 $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
	$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename($table)."as f left join ".tablename('xcommunity_region')."as r on f.regionid = r.id WHERE f.weid='{$_W['weid']}' AND f.black = 1 $condition ",$params);
	$pager  = pagination($total, $pindex, $psize);
	include $this->template('web/black/list');
}elseif ($op == 'delete') {
	if ($_W['isajax']) {
		if (empty($id)) {
			exit('缺少参数');
		}
		$r = pdo_delete($table,array('id' => $id));
		if ($r) {
			$result = array(
					'status' => 1,
				);
			echo json_encode($result);exit();
		}
	}
}elseif ($op == 'toblack') {
	if ($id) {
		pdo_query("UPDATE ".tablename($table)."SET black =0 WHERE id=:id",array(':id' => $id));
		echo json_encode(array('status' => 1));exit();
	}
}


