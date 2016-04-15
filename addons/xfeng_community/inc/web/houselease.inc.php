<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区租赁信息
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id        = intval($_GPC['id']);
	if ($op == 'list') {
		//搜索
		$condition = 'h.weid=:weid';
		$params[':weid'] = $_W['uniacid'];
		if (!empty($_GPC['category'])) {
			$condition .= " AND h.category = '{$_GPC['category']}'";
		}
		if (!empty($_GPC['status'])) {
			$condition .=" AND h.status = '{$_GPC['status']}'";
		}
		$starttime = strtotime($_GPC['birth']['start']) ;
		$endtime   = strtotime($_GPC['birth']['end']) ;
		if (!empty($starttime) && $starttime==$endtime) {
			$endtime = $endtime+86400-1;
		}
		if ($starttime && $endtime) {
			$condition .=" AND h.createtime between '{$starttime}' and '{$endtime}'";
		}
		//判断是否是操作员
		// $user = $this->user();
		// if ($user) {
		// 	$condition .=" AND regionid=:regionid";
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
				$condition .="AND h.regionid=:regionid";
				$params[':regionid'] = $user['regionid'];
			}else{
				$condition .=" AND r.pid =:pid";
				$params[':pid'] = $user['companyid'];
			}
			
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$sql    = "select h.*,r.city,r.dist,r.title from ".tablename('xcommunity_houselease')."as h left join".tablename('xcommunity_region')."as r on h.regionid = r.id  where $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list   = pdo_fetchall($sql,$params);
		$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_houselease")."as h left join".tablename('xcommunity_region')."as r on h.regionid = r.id where $condition",$params);
		$pager  = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('web/houselease/list');
	}elseif($op == 'add'){
		//编辑
		if ($id) {
			$item       = pdo_fetch("SELECT * FROM".tablename('xcommunity_houselease')."WHERE id=:id",array(':id' => $id));
			$images = unserialize($item['images']);
			if ($images) {
				$picid  = implode(',', $images);
				$imgs   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$picid})");
			}
			
		}
		include $this->template('web/houselease/add');
	}elseif ($op == 'delete') {
		//删除
		if ($id) {
			pdo_delete("xcommunity_houselease",array('id' => $id));
			message('房屋租赁信息删除成功。',referer(),'success');
		}
		
	}
