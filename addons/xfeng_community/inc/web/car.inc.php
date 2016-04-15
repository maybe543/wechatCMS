<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区拼车
 */


	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] :'list';
	$id = intval($_GPC['id']);
	if ($op == 'list') {
		$condition = 'f.weid=:weid';
		$params[':weid'] = $_W['uniacid'];
		if (!empty($_GPC['type'])) {
			$condition .=" AND f.type = '{$_GPC['type']}'";
		}
		//判断是否是操作员
		// $user = $this->user();
		// if ($user) {
		// 	$condition .="AND regionid=:regionid";
		// 	$params[':regionid'] = $user['regionid'];
		// }
		// if (!$user) {
		// 		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		// 		$regionid = intval($_GPC['regionid']);
		// 		if ($regionid) {
		// 			$condition .=" AND regionid =:regionid";
		// 			$params[':regionid'] = $regionid;
		// 		}
		// }
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
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
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$list = pdo_fetchall("SELECT f.*,r.city,r.dist,r.title as rtitle FROM".tablename('xcommunity_carpool')."as f left join ".tablename('xcommunity_region')."as r on f.regionid = r.id WHERE  $condition AND f.black = 0  LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_carpool')."as f left join ".tablename('xcommunity_region')."as r on f.regionid = r.id WHERE $condition AND f.black = 0",$params);
		$pager  = pagination($total, $pindex, $psize);
		include $this->template('web/car/list');
	}elseif ($op == 'delete') {
		if ($_W['isajax']) {
			if (empty($id)) {
				exit('缺少参数');
			}
			$r = pdo_delete("xcommunity_carpool",array('id' => $id));
			if ($r) {
				$result = array(
						'status' => 1,
					);
				echo json_encode($result);exit();
			}
		}
	}elseif ($op == 'toblack') {
		if ($id) {
			pdo_query("UPDATE ".tablename('xcommunity_carpool')."SET black =1 WHERE id=:id",array(':id' => $id));
			echo json_encode(array('state' => 1));
		}
	}


