<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区家政信息
 */
	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id        = intval($_GPC['id']);
	//查家政子类 家政主类ID=1
		$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid='{$_W['weid']}' AND type=1");
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
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
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
		$list   = pdo_fetchall("SELECT h.*,r.city,r.dist,r.title FROM".tablename('xcommunity_homemaking')."as h left join".tablename('xcommunity_region')."as r on h.regionid = r.id WHERE $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		$total  = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_homemaking')."as h left join".tablename('xcommunity_region')."as r on h.regionid = r.id WHERE $condition",$params);
		$pager  = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('web/homemaking/list');
	}elseif($op == 'add'){
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		//编辑
		$item  = pdo_fetch("SELECT * FROM".tablename('xcommunity_homemaking')."WHERE id=:id",array(':id' => $id));
		if (empty($item)) {
			message('信息不存在或已删除',referer(),'error');
		}
		
		if(checksubmit('submit')){
			$data = array(
			'status'               => $_GPC['status'],
			);
			pdo_update("xcommunity_homemaking",$data,array('id' => $id));
			message('修改成功',$this->createWebUrl('homemaking',array('op'=>'list')),'success');
		}
		include $this->template('web/homemaking/add');
	}elseif ($op == 'delete') {
		if ($id) {
			//删除
			pdo_delete("xcommunity_homemaking",array('id' => $id));
			message('家政服务信息删除成功。',referer(),'success');
		}
		
	}elseif ($op == 'category') {
		$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid=:weid AND type =1",array(':weid' => $_W['weid']));
		if (checksubmit('submit')) {
			$count = count($_GPC['names']);
			// print_r($count);exit();
			for ($i=0; $i < $count; $i++) { 
				$ids = $_GPC['ids'];
				$id  = trim(implode(',', $ids),',');
				$data = array(
									'name'   =>  $_GPC['names'][$i] ,
									'price' =>  $_GPC['prices'][$i],
									'weid'    =>  $_W['weid'],
									'type' => 1,
									'gtime' => $_GPC['gtimes'][$i],
									'description' => $_GPC['descriptions'][$i]
				 			);
				if($ids[$i]){
					$r = pdo_update("xcommunity_category",$data,array('id'=>$ids[$i]));
				}else{
					$r = pdo_insert("xcommunity_category",$data);
				}
			}
			message('提交成功',$this->createWebUrl('homemaking',array('op' => 'list')));

		}

		include $this->template('web/homemaking/category');
	}elseif ($op == 'del') {
		if ($id) {
			pdo_delete("xcommunity_category",array('id' => $id));
			message('删除成功。',referer(),'success');
		}
		
	}
