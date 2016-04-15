<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区活动
 */
	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id = intval($_GPC['id']);
	//判断是否是操作员
	$user = $this->user();
	if($op == 'add'){
		//查所有小区信息
		if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_activity')."WHERE id=:id",array(':id' => $id));
			$regs = unserialize($item['regionid']);
			$starttime = !empty($item['starttime']) ? date('Y-m-d',$item['starttime']) : date('Y-m-d',timestamp);
			$endtime = !empty($item['endtime']) ? date('Y-m-d',$item['endtime']) : date('Y-m-d',timestamp);
		}
		if (checksubmit('submit')) {
			$starttime = strtotime($_GPC['birth']['start']);
			$endtime   = strtotime($_GPC['birth']['end']);
			if (!empty($starttime) && $starttime==$endtime) {
				$endtime = $endtime+86400-1;
			}
			$data = array(
				'weid'       => $_W['weid'],
				'title'      => $_GPC['title'],
				'starttime'  => $starttime,
				'endtime'    => $endtime,
				'enddate'    => $_GPC['enddate'],
				'picurl'     => $_GPC['picurl'],
				'number'     => !empty($_GPC['number'])?$_GPC['number']:'1',
				'content'    => htmlspecialchars_decode($_GPC['content']),
				'status'     => $_GPC['status'],
				'createtime' => TIMESTAMP,
				'price'		 => $_GPC['price'],
			);
				if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
			if (empty($_GPC['id'])) {
				pdo_insert('xcommunity_activity',$data);
			}else{
				pdo_update('xcommunity_activity',$data,array('id' => $id));
			}
			message('更新成功',referer(),'success');
		}
		load()->func('tpl');
		include $this->template('web/activity/add');
	}elseif($op == 'list'){
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
		}
		if ($user) {
			$condition .=" AND uid='{$_W['uid']}'";
		}

		$sql = "SELECT * FROM".tablename('xcommunity_activity')."WHERE weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list   = pdo_fetchall($sql);
		// if ($user) {
		// 	$list1   = pdo_fetchall($sql);
		// 	foreach ($list1 as $key => $value) {
		// 		$regionids = unserialize($value['regionid']);
		// 		if (is_array($regionids)) {
		// 			if (@in_array($user['regionid'],$regionids)) {
		// 				$list[$key]['title'] = $value['title']; 
		// 				$list[$key]['starttime'] = $value['starttime'];
		// 				$list[$key]['endtime'] = $value['endtime'];
		// 				$list[$key]['price'] = $value['price'];
		// 				$list[$key]['status'] = $value['status'];
		// 				$list[$key]['createtime'] = $value['createtime'];
		// 				$list[$key]['id'] = $value['id'];	
		// 			}
		// 		}else{
		// 			if ($regionids == $user['regionid']) {
		// 				$list[$key]['title'] = $value['title']; 
		// 				$list[$key]['starttime'] = $value['starttime'];
		// 				$list[$key]['endtime'] = $value['endtime'];
		// 				$list[$key]['price'] = $value['price'];
		// 				$list[$key]['status'] = $value['status'];
		// 				$list[$key]['createtime'] = $value['createtime'];
		// 				$list[$key]['id'] = $value['id'];					
		// 			}
		// 		}
				
		// 	}
		// }else{
		// 	$list   = pdo_fetchall($sql);
		// }
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_activity')."WHERE weid='{$_W['weid']}'");
		$pager  = pagination($total, $pindex, $psize);
		//批量删除
		if (checksubmit('delete')) {
			$ids=$_GPC['id'];
			if (!empty($ids)) {
				foreach ($ids as $key => $id) {
					pdo_delete('xcommunity_activity',array('id' => $id));
				}
				message('删除成功',referer(),'success');
			}
		}
		// AJAX是否置顶
		if($_W['isajax'] && $_GPC['id']){
			$data = array();
			$data['status'] = intval($_GPC['status']);
			if(pdo_update('xcommunity_activity', $data, array('id' => $id)) !== false) {
					exit('success');
			}
			
		}
		include $this->template('web/activity/list');
	}elseif($op == 'delete'){
		pdo_delete('xcommunity_activity',array('id' => $id));
		message('删除成功',referer(),'success');
	}elseif ($op == 'order') {
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		$params = array();
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND r.truename LIKE :keyword";
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		$aid = intval($_GPC['id']);
		if ($aid) {
			$condition .=" AND r.aid = :aid";
			$params[':aid'] = $aid;
		}
		$list = pdo_fetchall("SELECT r.*,a.title as title,a.price as price FROM".tablename('xcommunity_res')."as r left join ".tablename('xcommunity_activity')."as a on r.aid = a.id WHERE r.weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_res')."as r left join ".tablename('xcommunity_activity')."as a on r.aid = a.id WHERE r.weid='{$_W['weid']}' $condition",$params);
		$pager  = pagination($total, $pindex, $psize);
		if (checksubmit('delete')) {
			pdo_delete('xcommunity_res', " id  IN  ('".implode("','", $_GPC['select'])."')");
			message('删除成功！',referer(),'success');
		}
		include $this->template('web/activity/order');
	}
	







