<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区商家
 */
defined('IN_IA') or exit('Access Denied');
	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'list';
	$id       = intval($_GPC['id']);
	//判断是否是操作员
	$user = $this->user();
	if ($op == 'users') {
		// $condition = '';
		// if ($user) {
		// 	$condition .=" AND uid = :uid";
		// 	$parms[':uid'] = $_W['uid'];
		// }
		
	}elseif ($op == 'category') {
		// if ($operation == 'list') {
		// 	//显示全部分类信息
		// 	$sql      = "select * from".tablename("xcommunity_category")."where parentid= 0 AND type=6";
		// 	$category = pdo_fetchall($sql);
		// 	$children = array();
		// 	foreach ($category as $key => $value) {
		// 		$sql  = "select *from".tablename("xcommunity_category")."where weid='{$_W['weid']}' and  parentid=".$value['id'];
		// 		$list = pdo_fetchall($sql);
		// 		$children[$value['id']] = $list;
		// 	}


		// 	include $this->template('web/business/category/list');
		// }elseif ($operation == 'add') {
		// 	$parentid = intval($_GPC['parentid']);
		// 	//编辑分类信息
		// 	if (!empty($id)) {
		// 		$category = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:id",array(':id' => $id));
		// 	}
		// 	//添加分类主ID
		// 	if (!empty($parentid)) {
		// 		$parent = pdo_fetch("SELECT * FROM".tablename('xcommunity_category')."WHERE id=:parentid",array(':parentid' => $parentid));
		// 	}
		// 	//提交
		// 	if (checksubmit('submit')) {
		// 		$data = array(
		// 			'name'         => $_GPC['catename'],
		// 			'parentid'     => 0,
		// 			'displayorder' => $_GPC['displayorder'],
		// 			'description'  => $_GPC['description'],
		// 			'enabled'      => 1,
		// 			'weid'         => $_W['weid'],
		// 			'type' => 6,
		// 			);
		// 		if (empty($parentid)) {
		// 			if (empty($id)) {
		// 				//添加主类
		// 				pdo_insert("xcommunity_category",$data);
		// 			}else{
		// 				//更新
		// 				$data['displayorder'] = $_GPC['displayorder'];
		// 				$data['name']         = $_GPC['catename'];
		// 				$data['description']  = $_GPC['description'];
		// 				pdo_update("xcommunity_category",$data,array('id'=>$id));
		// 			}					
		// 		}else{
		// 			//添加子类
		// 			if(empty($id)){
		// 					$data['parentid'] = $parentid;
		// 					pdo_insert("xcommunity_category",$data);
		// 			}else{
		// 				//更新子类
		// 				$data['parentid'] = $parentid;
		// 				$data['displayorder'] = $_GPC['displayorder'];
		// 				$data['name']         = $_GPC['catename'];
		// 				$data['description']  = $_GPC['description'];
		// 				pdo_update("xcommunity_category",$data,array('id'=>$id));
		// 			}
				
		// 		}
		// 		message('提交成功',referer(),'success');
		// 	}

		// 	include $this->template('web/business/category/add');
		// }
	}elseif ($op == 'list') {
			//店铺列表
			$condition = '';
			if ($user) {
				$condition .=" AND uid=:uid";
				$parms[':uid'] = $_W['uid'];
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT * FROM".tablename('xcommunity_dp')."WHERE weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_dp')."WHERE weid='{$_W['weid']}' $condition",$parms);
			$pager  = pagination($total, $pindex, $psize);

			include $this->template('web/business/dp/list');
	}elseif($op == 'dp'){
		//店铺管理
		if ($operation == 'list') {
			//店铺列表
			$condition = '';
			if ($user) {
				$condition .=" AND uid=:uid";
				$parms[':uid'] = $_W['uid'];
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT * FROM".tablename('xcommunity_dp')."WHERE weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_dp')."WHERE weid='{$_W['weid']}' $condition",$parms);
			$pager  = pagination($total, $pindex, $psize);

			include $this->template('web/business/dp/list');
		}elseif ($operation == 'add') {
			//添加店铺
			// $regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
			if ($id) {
				$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_dp')."WHERE id=:id",array(':id' => $id));
				if (empty($item)) {
					message('店铺不存在或已删除',referer(),'error');
				}
				$businesstime = explode('-',$item['businesstime']);
				// print_r($businesstime);
				$regs = iunserializer($item['regionid']);
			}
			if (checksubmit('submit')) {
				$industry = $_GPC['industry'];
				$reside = $_GPC['reside'];
				$baidumap = $_GPC['baidumap'];
				$data = array(
						'weid' => $_W['weid'],
						// 'regionid' => serialize($_GPC['regionid']),
						'sjname' => $_GPC['sjname'],
						'picurl' => $_GPC['picurl'],
						'contactname' => $_GPC['contactname'],
						'mobile' => $_GPC['mobile'],
						'phone' => $_GPC['phone'],
						'qq' => $_GPC['qq'],
						'businesstime' => $_GPC['open_time_start'] . '-' . $_GPC['open_time_end'],
						'address' => $_GPC['address'],
						'shopdesc' => htmlspecialchars_decode($_GPC['shopdesc']),
						'parent' => $industry['parent'],
						'child' => $industry['child'],
						'province' => $reside['province'],
						'city' => $reside['city'],
						'dist' => $reside['district'],
						'lat' => $baidumap['lat'],
						'lng' => $baidumap['lng'],
						'businessurl' => $_GPC['businessurl'],
						'createtime' => TIMESTAMP,

					);
				if (empty($id)) {
					if ($user) {
						$data['uid'] = $_W['uid'];
					}
					pdo_insert('xcommunity_dp',$data);
				}else{
					pdo_update('xcommunity_dp',$data,array('id' => $id));
				}
				message('提交成功',referer(),'success');

			}
			load()->func('tpl');
			include $this->template('web/business/dp/add');
		}elseif ($operation == 'set') {
			//个人设置
			if ($user) {
				$users = pdo_fetch("SELECT * FROM".tablename('xcommunity_users')."WHERE uid=:uid",array(':uid' => $_W['uid']));
			}

			include $this->template('web/business/dp/set');
		}elseif ($operation == 'cash') {
			//商家提现
			if ($user) {
				$users = pdo_fetch("SELECT * FROM".tablename('xcommunity_users')."WHERE uid=:uid",array(':uid' => $_W['uid']));
			}
			if (checksubmit('submit')) {
				if ($_GPC['cash'] > $users['balance']) {
					message('余额不足，无法提现',referer(),'error');
				}
				$data = array(
					'weid' => $_W['weid'],
					'ordersn' => date('YmdHi').random(10, 1),
					'price' => $_GPC['cash'],
					'type' => 'cash',
					'createtime' => TIMESTAMP,
					'uid' => $_W['uid'],
				);
				$r = pdo_insert('xcommunity_order',$data);
				if ($r) {
					pdo_update('xcommunity_users',array('balance' => $users['balance'] - number_format(floatval($_GPC['cash']),2)),array('id' => $users['id']));
					message('提交成功',$this->createWebUrl('business',array('op' => 'cash')),'success');
				}
				
			}

			include $this->template('web/business/dp/cash');
		}
	}elseif ($op == 'order') {
		//订单管理
		if ($operation == 'list') {
			$condition = '';
			if ($user) {
				$condition .=" AND o.uid=:uid";
				$parms[':uid'] = $_W['uid'];
			}
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$parms[':starttime'] = $starttime;
				$parms[':endtime'] = $endtime;
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (m.realname LIKE '%{$_GPC['member']}%' or m.mobile LIKE '%{$_GPC['member']}%')";
			}			
			$status = $_GPC['status'];
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT o.*,m.realname as realname,m.mobile as mobile,m.address as address FROM".tablename('xcommunity_order')."as o left join".tablename('xcommunity_member')."as m on o.from_user = m.openid WHERE o.weid='{$_W['weid']}' AND o.type = 'business' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			foreach ($list as $key => $value) {
				$list[$key]['cctime'] = date('Y-m-d H:i',$value['createtime']);
				$list[$key]['s'] = empty($value['status']) ? '未付' : '已付';
			}
			if ($_GPC['export'] == 1) {
				$this->export($list,array(
			            "title" => "商家订单数据-" . date('Y-m-d-H-i', time()),
			            "columns" => array(
			            	array(
			                    'title' => '订单号',
			                    'field' => 'ordersn',
			                    'width' => 30
			                ),
			                array(
			                    'title' => '姓名',
			                    'field' => 'realname',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '手机号',
			                    'field' => 'mobile',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '总价',
			                    'field' => 'price',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '状态',
			                    'field' => 's',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '下单时间',
			                    'field' => 'cctime',
			                    'width' => 15
			                ),
			            )
			        ));
			}
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_order')."as o left join".tablename('xcommunity_member')."as m on o.from_user = m.openid WHERE o.weid='{$_W['weid']}' AND o.type = 'business' $condition",$parms);
			$pager  = pagination($total, $pindex, $psize);
			load()->func('tpl');
			include $this->template('web/business/order/list');
		}elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			if ($id) {
				$item = pdo_fetch("SELECT o.*,m.realname as realname,m.mobile as mobile,m.address as address FROM " . tablename('xcommunity_order') . "as o left join".tablename('xcommunity_member')."as m on o.from_user = m.openid WHERE o.id = :id", array(':id' => $id));
				if ($item['gid']) {
					$goods = pdo_fetch("SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND id=:id",array(':id' => $item['gid'],':weid' => $_W['uniacid']));
				}
			}
			include $this->template('web/business/order/detail');
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			if ($id) {
				$r = pdo_delete('xcommunity_order',array('id' => $id));
				if ($r) {
					message('删除成功',referer(),'success');
				}
			}
		}
		
	}elseif($op == 'del'){
		//删除店铺
		if (pdo_delete('xcommunity_dp',array('id' => $id))) {
			$result = array(
					'status' => 1,
				);
			echo json_encode($result);exit();
		}
	}elseif ($op == 'cash') {
		//余额提现
		if ($operation == 'list') {
			$condition = '';
			if ($user) {
				$condition .=" AND uid=:uid";
				$parms[':uid'] = $_W['uid'];
			}
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT * FROM".tablename('xcommunity_order')."WHERE weid='{$_W['weid']}' AND type='cash' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_order')."WHERE weid='{$_W['weid']}' AND type='cash' $condition",$parms);
			$pager  = pagination($total, $pindex, $psize);

			include $this->template('web/business/cash/list');
		}elseif ($operation == 'del') {
			//删除提现订单
			if (pdo_delete('xcommunity_order',array('id' => $id))) {
				$result = array(
						'status' => 1,
					);
				echo json_encode($result);exit();
			}
		}
		
	}elseif($op == 'verify'){
		//处理状态
		$id = intval($_GPC['id']);
		if ($id) {
			if ($_W['isajax']) {
				$r = pdo_update('xcommunity_order',array('status' => 1),array('id' => $id));
				if ($r) {
					$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
				}
			}
		}
		// $type = $_GPC['type'];
		// $data = intval($_GPC['data']);
		// if (in_array($type, array('status'))) {
		// 	$data = ($data==1?'0':'1');
		// 	pdo_update("xcommunity_order", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
		// 	die(json_encode(array("result" => 1, "data" => $data)));
		// }
	}elseif ($op == 'coupon') {
		//卡券核销
		if ($operation == 'list') {
			
			$condition = '';
			if ($user) {
				$condition .=" AND o.uid=:uid";
				$parms[':uid'] = $_W['uid'];
			}
			$enable = intval($_GPC['enable']);
			if ($enable) {
				$condition .=" AND o.enable=:enable";
				$parms[':enable'] = $enable;
			}
			$code = $_GPC['code'];
			if ($code) {
				$condition .=" AND o.couponsn=:couponsn";
				$parms[':couponsn'] = $code;
			}
			// echo $code;exit();
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT o.*,g.title as title  FROM".tablename('xcommunity_order')."as o left join ".tablename('xcommunity_goods')."as g on o.gid = g.id WHERE o.weid='{$_W['weid']}' AND o.type='business' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_order')."as o left join ".tablename('xcommunity_goods')."as g on o.gid = g.id WHERE o.weid='{$_W['weid']}' AND o.type='business' $condition",$parms);
			$pager  = pagination($total, $pindex, $psize);
			include $this->template('web/business/coupon/list');
		}elseif ($operation == 'use') {
			if ($_W['isajax']) {
				if (empty($id)) {
					exit('缺少参数');
				}
				$r = pdo_update('xcommunity_order',array('enable' => 2,'usetime' => TIMESTAMP),array('id' => $id));
				if ($r) {
					$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
				}
			}
		}
		
	}elseif ($op == 'goods') {
		//商品管理
		$dpid = intval($_GPC['dpid']);
		if ($operation == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$condition = " weid =:weid";
			$parms[':weid'] = $_W['uniacid'];
			$condition .= " AND dpid =:dpid";
			$parms[':dpid'] = $dpid;
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
			}
			if (isset($_GPC['status'])) {
				$condition .= " AND status = '" . intval($_GPC['status']) . "'";
			}
			$sql = "SELECT * FROM".tablename('xcommunity_goods')."WHERE $condition AND type = 2 LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_goods')."WHERE $condition AND type = 2 ",$parms);
			$pager  = pagination($total, $pindex, $psize);


			include $this->template('web/business/goods/list');
		}elseif ($operation == 'add') {
			if (empty($dpid)) {
				message('缺少参数',referer(),'error');	
			}
			if ($id) {
				$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_goods')."WHERE id=:id AND weid=:weid",array(':id' => $id,':weid' => $_W['uniacid']));
			}
			if (checksubmit('submit')) {
				$data = array(
						'weid' => $_W['weid'],
						'title' => $_GPC['title'],
						'status' => intval($_GPC['status']),
						'isrecommand' => intval($_GPC['isrecommand']),
						'thumb' => $_GPC['thumb'],
						'marketprice' => $_GPC['marketprice'],
						'productprice' => $_GPC['productprice'],
						'total' => intval($_GPC['total']),
						'content' => htmlspecialchars_decode($_GPC['content']),
						'description' => htmlspecialchars_decode($_GPC['description']),
						'dpid' => $dpid,
						'type' => 2,
						'createtime' => TIMESTAMP
					);
				if ($id) {
					pdo_update('xcommunity_goods',$data,array('id' => $id));
				}else{
					pdo_insert('xcommunity_goods',$data);
				}
				message('添加成功',referer(),'success');
			}
			load()->func('tpl');
			include $this->template('web/business/goods/add');
		}elseif ($operation == 'delete') {
			if ($_W['isajax']) {
				if (empty($id)) {
					exit('缺少参数');
				}
				$r = pdo_delete("xcommunity_goods",array('id' => $id));
				if ($r) {
					$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
				}
			}
		}
	}elseif ($op == 'setgoodsproperty') {
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('isrecommand', 'status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}elseif ($op == 'rank') {
		//评价管理
		$dpid = intval($_GPC['dpid']);
		if ($operation == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 20;
			$sql = "SELECT r.*,m.realname as realname  FROM".tablename('xcommunity_rank')."as r left join ".tablename('xcommunity_member')." as m on r.openid = m.openid WHERE  r.dpid = :dpid AND r.type = 1 LIMIT ".($pindex - 1) * $psize.','.$psize;
			$parms[':dpid'] = $dpid;
			$list   = pdo_fetchall($sql,$parms);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_rank')."as r left join ".tablename('xcommunity_member')." as m on r.openid = m.openid WHERE r.dpid = :dpid AND r.type = 1 ",$parms);
			$pager  = pagination($total, $pindex, $psize);


			include $this->template('web/business/rank/list');
		}
	}









	