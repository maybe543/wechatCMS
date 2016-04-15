<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台超市管理
 */
	global $_W,$_GPC;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'order';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'list';
	$regions = $this->regions();
	$id = intval($_GPC['id']);
	//判断是否是操作员
	$user = $this->user();
	if($op == 'category'){
		//商品分类
		if ($operation == 'list') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('xcommunity_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', referer(), 'success');
			}
			//管理商品分类
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 10;
			$sql    = "select * from ".tablename("xcommunity_category")."where  weid = {$_W['weid']} AND type =5 ORDER BY displayorder DESC LIMIT ".($pindex - 1) * $psize.','.$psize;
			
			$list   = pdo_fetchall($sql);
			$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_category")."where  weid = {$_W['weid']} AND type =5 ORDER BY displayorder DESC");
			$pager  = pagination($total, $pindex, $psize);
			// AJAX是否显示
			if($_W['isajax'] && $id){
				$data = array();
				$data['enabled'] = intval($_GPC['enabled']);
				if(pdo_update('xcommunity_category', $data, array('id' => $id)) !== false) {
						exit('success');
				}
				
			}
			include $this->template('web/shopping/category/list');

		}elseif ($operation == 'add') {
			
			if (!empty($id)) {
				$category = pdo_fetch("SELECT * FROM " . tablename('xcommunity_category') . " WHERE id = :id",array(':id' => $id));
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['catename'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['uniacid'],
					'name' => $_GPC['catename'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'description' => $_GPC['description'],
					'thumb' => $_GPC['thumb'],
					'type' => 5
				);
				if (!empty($id)) {
					pdo_update('xcommunity_category', $data, array('id' => $id));
					load()->func('file');
					file_delete($_GPC['thumb_old']);
				} else {
					pdo_insert('xcommunity_category', $data);
				}
				message('更新分类成功！', referer(), 'success');
			}
			load()->func('tpl');
			include $this->template('web/shopping/category/add');
		}elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid FROM " . tablename('xcommunity_category') . " WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！',referer(), 'error');
			}
			pdo_delete('xcommunity_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', referer(), 'success');
		}
	}elseif($op == 'goods'){
		//商品管理
		
		$category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_category') . " WHERE weid = '{$_W['uniacid']}' AND type = 5 AND enabled = 1 ORDER BY displayorder DESC", array(), 'id');
		if ($operation == 'add') {

			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_goods') . " WHERE id = :id", array(':id' => $id));
				$regs = iunserializer($item['regionid']);
				if (empty($item)) {
					message('抱歉，商品不存在或是已经删除！', '', 'error');
				}
				$piclist1 = unserialize($item['thumb_url']);
				$piclist = array();
				if(is_array($piclist1)){
					foreach($piclist1 as $p){
						$piclist[] = is_array($p)?$p['attachment']:$p;
					}
				}
				
			}
			
			if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
			
			if (empty($category)) {
				message('抱歉，请您先添加商品分类！', $this->createWebUrl('shopping', array('operation' => 'add','op' => 'category')), 'error');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['goodsname'])) {
					message('请输入商品名称！');
				}
				if (empty($_GPC['pcate'])) {
					message('请选择商品分类！');
				}
				if(empty($_GPC['thumbs'])){
					$_GPC['thumbs'] = array();
				}
				$data = array(
					'weid' => intval($_W['uniacid']),
					'displayorder' => intval($_GPC['displayorder']),
					'title' => $_GPC['goodsname'],
					'pcate' => intval($_GPC['pcate']),
					'thumb'=>$_GPC['thumb'],
					'isrecommand' => intval($_GPC['isrecommand']),
					'content' => htmlspecialchars_decode($_GPC['content']),
					'unit' => $_GPC['unit'],
					'createtime' => TIMESTAMP,
					'total' => intval($_GPC['total']),
					'marketprice' => $_GPC['marketprice'],
					'productprice' => $_GPC['productprice'],
					'credit' => intval($_GPC['credit']),
					'status' => intval($_GPC['status']),
					'type' => 1,
				);
				if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
				
				if(is_array($_GPC['thumbs'])){
					$data['thumb_url'] = serialize($_GPC['thumbs']);
				}
				if (empty($id)) {
					pdo_insert('xcommunity_goods', $data);
					$id = pdo_insertid();
				} else {
					unset($data['createtime']);
					pdo_update('xcommunity_goods', $data, array('id' => $id));
				}
				message('商品更新成功！', referer(), 'success');
			}
			load()->func('tpl');
			include $this->template('web/shopping/goods/add');
		} elseif ($operation == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}
			if (isset($_GPC['status'])) {
				$condition .= " AND status = '" . intval($_GPC['status']) . "'";
			}
			if ($user) {
				$condition .=" AND uid='{$_W['uid']}'";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_goods') . " WHERE weid = '{$_W['uniacid']}' AND type = 1 $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_goods') . " WHERE weid = '{$_W['uniacid']}' AND type = 1 $condition");
			$pager = pagination($total, $pindex, $psize);
			include $this->template('web/shopping/goods/list');
		} elseif ($operation == 'delete') {
			
			$row = pdo_fetch("SELECT id, thumb,thumb_url FROM " . tablename('xcommunity_goods') . " WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，商品不存在或是已经被删除！');
			}
			//删除商品图
			load()->func('file');
			if (!empty($row['thumb'])) {
				
				file_delete($row['thumb']);
			}
			//删除多图
			$piclist1 = unserialize($row['thumb_url']);
			if(is_array($piclist1)){
				foreach($piclist1 as $p){
					file_delete($p);
				}
			}
			
			pdo_delete('xcommunity_goods',array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
		
	}elseif($op == 'slide'){
		//幻灯管理
		load()->func('tpl');
		if ($operation == 'display') {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_slide') . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
		} elseif ($operation == 'post') {
			$id = intval($_GPC['id']);
			if ($id) {
				$adv = pdo_fetch("select * from " . tablename('xcommunity_shopping_slide') . " where id=:id and weid=:weid limit 1", array(":id" => $id, ":weid" => $_W['uniacid']));
			}
			if (checksubmit('submit')) {
				$data = array(
					'weid' => $_W['uniacid'],
					'advname' => $_GPC['advname'],
					'link' => $_GPC['link'],
					'enabled' => intval($_GPC['enabled']),
					'displayorder' => intval($_GPC['displayorder']),
					'thumb'=>$_GPC['thumb'],
				);
				if (!empty($id)) {
					pdo_update('xcommunity_shopping_slide', $data, array('id' => $id));
				} else {
					pdo_insert('xcommunity_shopping_slide', $data);
					$id = pdo_insertid();
				}
				message('更新幻灯片成功！',referer(), 'success');
			}
			
		} elseif ($operation == 'delete') {
			$id = intval($_GPC['id']);
			$adv = pdo_fetch("SELECT id FROM " . tablename('xcommunity_shopping_slide') . " WHERE id = '$id' AND weid=" . $_W['uniacid'] . "");
			if (empty($adv)) {
				message('抱歉，幻灯片不存在或是已经被删除！', referer(), 'error');
			}
			pdo_delete('xcommunity_shopping_slide', array('id' => $id));
			message('幻灯片删除成功！', referer(), 'success');
		} else {
			message('请求方式不存在');
		}
			
		

		include $this->template('shopping_slide');
	}elseif ($op == 'setgoodsproperty') {
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('isrecommand', 'status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_goods", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}elseif ($op == 'order') {
		load()->func('tpl');
		if ($operation == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;

			// $sendtype = !isset($_GPC['sendtype']) ? 0 : $_GPC['sendtype'];
			$condition = " o.weid = :weid";
			$paras = array(':weid' => $_W['uniacid']);
			if (empty($starttime) || empty($endtime)) {
				$starttime = strtotime('-1 month');
				$endtime = time();
			}
			if (!empty($_GPC['time'])) {
				$starttime = strtotime($_GPC['time']['start']);
				$endtime = strtotime($_GPC['time']['end']) + 86399;
				$condition .= " AND o.createtime >= :starttime AND o.createtime <= :endtime ";
				$paras[':starttime'] = $starttime;
				$paras[':endtime'] = $endtime;
			}
			if (!empty($_GPC['paytype'])) {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			} elseif ($_GPC['paytype'] === '0') {
				$condition .= " AND o.paytype = '{$_GPC['paytype']}'";
			}
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND o.ordersn LIKE '%{$_GPC['keyword']}%'";
			}
			if (!empty($_GPC['member'])) {
				$condition .= " AND (a.realname LIKE '%{$_GPC['member']}%' or a.mobile LIKE '%{$_GPC['member']}%')";
			}
			$status = $_GPC['status'];
			if ($status != '') {
				$condition .= " AND o.status = '" . intval($status) . "'";
			}
			// if (!empty($sendtype)) {
			// 	$condition .= " AND o.sendtype = '" . intval($sendtype) . "' AND o.status != '3'";
			// }
			if ($user) {
				$condition .=" AND o.uid=:uid";
				$paras[':uid'] = $_W['uid'];
			}
			$sql = "select o.* , a.realname,a.mobile from ".tablename('xcommunity_order')." o"
					." left join ".tablename('xcommunity_member')." a on o.from_user = a.openid "
					. " where $condition AND o.type = 'shopping' ORDER BY o.status DESC, o.createtime DESC "
					. "LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
			$list = pdo_fetchall($sql,$paras);

			$paytype = array (
					'0' => array('css' => 'default', 'name' => '未支付'),
					'1' => array('css' => 'danger','name' => '余额支付'),
					'2' => array('css' => 'info', 'name' => '在线支付'),
					'3' => array('css' => 'warning', 'name' => '货到付款'),
					'4' => array('css' => 'info', 'name' => '后台支付')
			);
			$orderstatus = array (
					'-1' => array('css' => 'default', 'name' => '已关闭'),
					'0' => array('css' => 'danger', 'name' => '待付款'),
					'1' => array('css' => 'info', 'name' => '待发货'),
					'2' => array('css' => 'warning', 'name' => '待收货'),
					'3' => array('css' => 'success', 'name' => '已完成')
			);
			foreach ($list as $key => $value) {
				$list[$key]['cctime'] = date('Y-m-d H:i',$value['createtime']);
				$s = $value['status'];
				$list[$key]['statuscss'] = $orderstatus[$value['status']]['css'];
				$list[$key]['status'] = $orderstatus[$value['status']]['name'];
				// $value['statuscss'] = $orderstatus[$value['status']]['css'];
				// $value['status'] = $orderstatus[$value['status']]['name'];
				if ($s < 1) {
					$list[$key]['css'] = $paytype[$s]['css'];
					$list[$key]['paytype'] = $paytype[$s]['name'];
					// $value['css'] = $paytype[$s]['css'];
					// $value['paytype'] = $paytype[$s]['name'];
					continue;
				}
				$list[$key]['css'] = $paytype[$value['paytype']]['css'];
				// $value['css'] = $paytype[$value['paytype']]['css'];
				if ($value['paytype'] == 2) {
					if (empty($value['transid'])) {
						$list[$key]['paytype'] = '支付宝支付';
						// $value['paytype'] = '支付宝支付';
					} else {
						$list[$key]['paytype'] = '微信支付';
						// $value['paytype'] = '微信支付';
					}
				} else {
					$list[$key]['paytype'] = $paytype[$value['paytype']]['name'];
					// $value['paytype'] = $paytype[$value['paytype']]['name'];
				}
				$list[$key]['ordersn'] = chunk_split($value['ordersn']);
			}
			//print_r($list);exit();
			// foreach ($list as $key => $value) {
			// 	$list[$key]['cctime'] = date('Y-m-d H:i',$value['createtime']);
			// }
			// print_r($list);exit();
			if ($_GPC['export'] == 1) {
				$this->export($list,array(
			            "title" => "超市订单数据-" . date('Y-m-d-H-i', time()),
			            "columns" => array(
			            	array(
			                    'title' => '订单号',
			                    'field' => 'ordersn',
			                    'width' => 24
			                ),
			                array(
			                    'title' => '收货姓名(或自提人)',
			                    'field' => 'realname',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '手机号',
			                    'field' => 'mobile',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '支付方式',
			                    'field' => 'paytype',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '总价',
			                    'field' => 'price',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '状态',
			                    'field' => 'status',
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
			$total = pdo_fetchcolumn(
						'SELECT COUNT(*) FROM ' . tablename('xcommunity_order') . " o "
						." left join ".tablename('xcommunity_member')." a on o.from_user = a.openid "
						." WHERE $condition AND o.type = 'shopping'", $paras);
			$pager = pagination($total, $pindex, $psize);
			include $this->template('web/shopping/order/list');
		} elseif ($operation == 'detail') {
			$id = intval($_GPC['id']);
			$item = pdo_fetch("SELECT o.*,m.realname as realname,m.mobile as mobile,m.address as address FROM " . tablename('xcommunity_order') . "as o left join".tablename('xcommunity_member')."as m on o.from_user = m.openid WHERE o.id = :id", array(':id' => $id));
			//获取商品信息
			$goods = pdo_fetchall("SELECT g.*, o.total,o.price as orderprice FROM " . tablename('xcommunity_order_goods') .
					" o left join " . tablename('xcommunity_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$id}'");
			$item['goods'] = $goods;
			$title = '';
			$count = count($goods);
			// print_r($count);exit();
			if ($count == 1) {
				foreach ($goods as $key => $value) {
					$title = $value['title'];
				}
			}else{
				foreach ($goods as $key => $value) {
					$title .= $value['title'].',';
				}
			}
			if (empty($item)) {
				message("抱歉，订单不存在!", referer(), "error");
			}
			if (checksubmit('confirmsend')) {
				//$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 1);
				}
				//发货短信提醒
				$sms = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_smsid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));

				if ($sms['shopping_id']) {
					load()->func('communication');
					$tpl_id    = $sms['shopping_id'];
					$expresscom = $_GPC['expresscom'];
					$expresssn = $_GPC['expresssn'];
					$tpl_value = urlencode("#express_name#=$expresscom&#express_code#=$expresssn");
					$appkey    = $sms['sms_account'];
					$params    = "mobile=".$address['mobile']."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
					$url       = 'http://v.juhe.cn/sms/send';
					$content   = ihttp_post($url,$params);
				}
				//微信模板通知提醒
				$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));

				if ($tpl['shopping_tplid']) {
					$openid = $item['from_user'];
					$url = '';
					$template_id = $tpl['shopping_tplid'];
					$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
					$content = array(
							'first' => array(
									'value' => '您的订单已经标记发货，请留意查收。',
								),
							'orderProductPrice' => array(
									'value' => $item['goodsprice'].'元',
								),
							'orderProductName' => array(
									'value' => $title,
								),
							'orderAddress'    => array(
									'value' => $item['realname'].','.$item['address'].','.$item['mobile'],
								),
							'orderName'    => array(
									'value' => $item['ordersn'],
								),
							'remark'    => array(
								'value' => '有任何问题请随时与我们联系，谢谢。',
							),	
						);
					$this->sendtpl($openid,$url,$template_id,$content);
				}
				pdo_update(
					'xcommunity_order',
					array(
						'status' => 2,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('发货操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelsend')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['cancelreson']);
				}
				pdo_update(
					'xcommunity_order',
					array(
						'status' => 1,
						'remark' => $_GPC['remark'],
					),
					array('id' => $id)
				);
				message('取消发货操作成功！', referer(), 'success');
			}
			if (checksubmit('finish')) {
				pdo_update('xcommunity_order', array('status' => 3, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancel')) {
				pdo_update('xcommunity_order', array('status' => 1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('取消完成订单操作成功！', referer(), 'success');
			}
			if (checksubmit('cancelpay')) {
				pdo_update('xcommunity_order', array('status' => 0, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id, false);
				//减少积分
				$this->setOrderCredit($id, false);

				message('取消订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('confrimpay')) {
				pdo_update('xcommunity_order', array('status' => 1, 'paytype' => 4, 'remark' => $_GPC['remark']), array('id' => $id));
				//设置库存
				$this->setOrderStock($id);
				//增加积分
				$this->setOrderCredit($id);
				message('确认订单付款操作成功！', referer(), 'success');
			}
			if (checksubmit('close')) {
				$item = pdo_fetch("SELECT transid FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['transid'])) {
					$this->changeWechatSend($id, 0, $_GPC['reson']);
				}
				pdo_update('xcommunity_order', array('status' => -1, 'remark' => $_GPC['remark']), array('id' => $id));
				message('订单关闭操作成功！', referer(), 'success');
			}
			if (checksubmit('open')) {
				$item = pdo_fetch("SELECT paytype FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $id));
				if (!empty($item['paytype']) && $item['paytype'] != 3) {
					$status = 1;
				}
				pdo_update('xcommunity_order', array('status' => $status, 'remark' => $_GPC['remark']), array('id' => $id));
				message('开启订单操作成功！', referer(), 'success');
			}

			include $this->template('web/shopping/order/detail');
		} elseif ($operation == 'delete') {
			/*订单删除*/
			$orderid = intval($_GPC['id']);
			if (pdo_delete('xcommunity_order', array('id' => $orderid))) {
				message('订单删除成功', $this->createWebUrl('shopping',array('op' => 'order','operation' => 'list')), 'success');
			}
		}


	}


















