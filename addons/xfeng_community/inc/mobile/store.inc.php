<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端独立小区超市
 */
	
	global $_GPC,$_W;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
	//判断是否注册，只有注册后，才能进入
	$member = $this->changemember();
	$tel = $this->linkway();
	if($op == 'list' || $op == 'more'){
		// $name = "xiaofeng_store";
		// $um = pdo_fetch("SELECT * FROM".tablename('uni_account_modules')."WHERE module='{$name}' AND uniacid='{$_W['uniacid']}'");
		// if (empty($um)) {
		// 	message('还没有购买便利店模块,请联系总管理员获取',referer(),'error');
		// }
		//幻灯片
		$advs = pdo_fetchall("select * from " . tablename('xiaofeng_store_slide') . " where enabled=1 and uniacid= '{$_W['uniacid']}'");
		foreach ($advs as &$adv) {
			if (substr($adv['link'], 0, 5) != 'http:') {
				$adv['link'] = "http://" . $adv['link'];
			}
		}
		//首页公告
		$set = pdo_fetch("SELECT * FROM".tablename('xiaofeng_store_set')."WHERE weid=:weid",array(':weid' => $_W['uniacid']));
		//首页推荐
		$pindex = max(1, intval($_GPC['rpage']));
		$psize = 10;
		$condition = ' and isrecommand=1';
		if (!empty($_GPC['keywords'])) {
			$condition .= " AND title LIKE '%{$_GPC['keywords']}%'";
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename('xiaofeng_store_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND status = '1' $condition AND regionid = :regionid ORDER BY displayorder DESC LIMIT ".($pindex - 1) * $psize.','.$psize,array(':regionid' => $member['regionid']));
		
		if ($op == 'more') {
			
			include $this->template('list_more');exit();
		}

		include $this->template('store/list');
	}elseif($op == 'list2' || $op == 'list2_more'){
		$pindex = max(1, intval($_GPC["page"]));
		$psize = 10;
		$condition = " ";
		$pcate = intval($_GPC['pcate']);
		if ($pcate) {
			$condition .="AND pcate=:pcate";
			$params[':pcate'] = $pcate;
		}

		$category = pdo_fetchall("SELECT * FROM " . tablename('xiaofeng_store_category') . " WHERE uniacid = '{$_W['uniacid']}' and enabled=1 AND regionid = '{$member['regionid']}' ORDER BY displayorder DESC", array(), 'id');

		$list = pdo_fetchall("SELECT * FROM " . tablename('xiaofeng_store_goods') . " WHERE uniacid = '{$_W['uniacid']}' AND status = '1'  AND regionid=:regionid LIMIT ".($pindex - 1) * $psize.','.$psize,array(':regionid' => $member['regionid']));

		if ($op == 'list2_more') {
			
			include $this->template('list2_more');exit();
		}
		include $this->template('store/list2');
	}elseif ($op == 'detail') {
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('xiaofeng_store_goods') . " WHERE id = :id", array(':id' => $goodsid));
		if (empty($goods)) {
			message('抱歉，商品不存在或是已经被删除！');
		}

		$piclist1 = array(array("attachment" => $goods['thumb']));
		$piclist = array();
		if (is_array($piclist1)) {
			foreach($piclist1 as $p){
				$piclist[] = is_array($p)?$p['attachment']:$p;
			}
		}
		if ($goods['thumb_url'] != 'N;') {
			$urls = unserialize($goods['thumb_url']);
			if (is_array($urls)) {
				foreach($urls as $p){
					$piclist[] = is_array($p)?$p['attachment']:$p;
				}
			}
		}
		$marketprice = $goods['marketprice'];
		$productprice= $goods['productprice'];
		$stock = $goods['total'];
		
		$carttotal = $this->getCartTotal();
		include $this->template('store/detail');
	}elseif ($op == 'mycart') {
		if ($operation == 'add') {
			$goodsid = intval($_GPC['id']);
			//print_r($goodsid);exit();
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$goods = pdo_fetch("SELECT * FROM " . tablename('xiaofeng_store_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				$result['message'] = '抱歉，该商品不存在或是已经被删除！';
				message($result, '', 'ajax');
			}
			$marketprice = $goods['marketprice'];
		
			$row = pdo_fetch("SELECT id, total FROM " . tablename('xiaofeng_store_cart') . " WHERE from_user = :from_user AND uniacid = '{$_W['uniacid']}' AND goodsid = :goodsid  and optionid=:optionid", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid,':optionid'=>$optionid));
			if ($row) {
				$t = $total + $row['total'];
				if ($t > $goods['total']) {
					$result = array(
						'result' => 0,
						'maxbuy' => $goods['total']
					);

					die(json_encode($result));exit();
				}
			}
			if ($row == false) {
				//不存在
				$data = array(
					'uniacid' => $_W['uniacid'],
					'goodsid' => $goodsid,
					'goodstype' => $goods['type'],
					'marketprice' => $marketprice,
					'from_user' => $_W['fans']['from_user'],
					'total' => $total,

				);
				pdo_insert('xiaofeng_store_cart', $data);
			} else {
				//累加最多限制购买数量
				$t = $total + $row['total'];
				
				//存在
				$data = array(
					'marketprice' => $marketprice,
					'total' => $t,
				
				);
				pdo_update('xiaofeng_store_cart', $data, array('id' => $row['id']));
			}
			//返回数据
			$carttotal = $this->getCartTotal1();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);

			die(json_encode($result));
		} else if ($operation == 'clear') {
			pdo_delete('xiaofeng_store_cart', array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid']));
			die(json_encode(array("result" => 1)));
		} else if ($op == 'remove') {
			$id = intval($_GPC['id']);
			pdo_delete('xiaofeng_store_cart', array('from_user' => $_W['fans']['from_user'], 'uniacid' => $_W['uniacid'], 'id' => $id));
			die(json_encode(array("result" => 1, "cartid" => $id)));
		} else if ($operation == 'update') {
			// $id = intval($_GPC['id']);
			// $num = intval($_GPC['num']);
			// $sql = "update " . tablename('xiaofeng_store_cart') . " set total=$num where id=:id";
			// pdo_query($sql, array(":id" => $id));
			// die(json_encode(array("result" => 1)));
			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('缺少参数',referer(),'error');
			}
			$num = intval($_GPC['num']);
			$good = pdo_fetch("SELECT c.total as ctotal ,g.total as gtotal FROM".tablename('xiaofeng_store_cart')."as c left join".tablename('xiaofeng_store_goods')."as g on c.goodsid = g.id WHERE c.id=:id",array(':id' => $id));

			if ($good) {
				$t = $num;
				if ($t == $good['gtotal'] || $t < $good['gtotal']) {
					$sql = "update " . tablename('xiaofeng_store_cart') . " set total=$num where id=:id";
					pdo_query($sql, array(":id" => $id));
					die(json_encode(array("result" => 1)));
				}else{
					$result = array(
						'result' => 0,
						'maxbuy' => $good['gtotal']
					);

					die(json_encode($result));exit();
				}
			}
		} else {
			$list = pdo_fetchall("SELECT * FROM " . tablename('xiaofeng_store_cart') . " WHERE  uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  title, thumb, marketprice, unit, total,maxbuy FROM " . tablename('xiaofeng_store_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					//属性
					// $option = pdo_fetch("select title,marketprice,stock from " . tablename("xiaofeng_store_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
					// if ($option) {
					// 	$goods['title'] = $goods['title'];
					// 	$goods['optionname'] = $option['title'];
					// 	$goods['marketprice'] = $option['marketprice'];
					// 	$goods['total'] = $option['stock'];
					// }
					$item['goods'] = $goods;
					$item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
					$totalprice += $item['totalprice'];
				}
				unset($item);
			}
		}
		include $this->template('store/cart');
	}elseif ($op == 'confirm') {
		// checkauth();
		$totalprice = 0;
		$allgoods = array();
		$id = intval($_GPC['id']);
		// $optionid = intval($_GPC['optionid']);
		$total = intval($_GPC['total']);
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnurl = ""; //当前连接
		if (!empty($id)) {
			$item = pdo_fetch("select * from " . tablename("xiaofeng_store_goods") . " where id=:id limit 1", array(":id" => $id));
			// if ($item['istime'] == 1) {
			// 	if (time() > $item['timeend']) {
			// 		$backUrl = $this->createMobileUrl('store', array('op' => 'detail','id' => $id));
			// 		$backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
			// 		message('抱歉，商品限购时间已到，无法购买了！', $backUrl, "error");
			// 	}
			// }
			// if (!empty($optionid)) {
			// 	$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xiaofeng_store_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
			// 	if ($option) {
			// 		$item['optionid'] = $optionid;
			// 		$item['title'] = $item['title'];
			// 		$item['optionname'] = $option['title'];
			// 		$item['marketprice'] = $option['marketprice'];
			// 		$item['weight'] = $option['weight'];
			// 	}
			// }
			$item['stock'] = $item['total'];
			$item['total'] = $total;
			$item['totalprice'] = $total * $item['marketprice'];
			$allgoods[] = $item;
			$totalprice+= $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;
			$returnurl = $this->createMobileUrl("store", array('op' => 'confirm',"id" => $id, "optionid" => $optionid, "total" => $total));
		}
		if (!$direct) {
			//如果不是直接购买（从购物车购买）
			$list = pdo_fetchall("SELECT * FROM " . tablename('xiaofeng_store_cart') . " WHERE  uniacid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select id,thumb,title,weight,marketprice,total,type,totalcnf,sales,unit,uid from " . tablename("xiaofeng_store_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					// $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xiaofeng_store_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
					// if ($option) {
					// 	$item['optionid'] = $g['optionid'];
					// 	$item['title'] = $item['title'];
					// 	$item['optionname'] = $option['title'];
					// 	$item['marketprice'] = $option['marketprice'];
					// 	$item['weight'] = $option['weight'];
					// }
					$item['stock'] = $item['total'];
					$item['total'] = $g['total'];
					$item['totalprice'] = $g['total'] * $item['marketprice'];
					$allgoods[] = $item;
					$totalprice+= $item['totalprice'];
					if ($item['type'] == 1) {
						$needdispatch = true;
					}
				}
				unset($g);
			}
			$returnurl = $this->createMobileUrl("store",array('op' => 'confirm'));
		}
		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('store' ,array('op' => 'myorder')));
			exit();
		}
		//配送方式
		$dispatch = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice from " . tablename("xiaofeng_store_dispatch") . " WHERE uniacid = {$_W['uniacid']} order by displayorder desc");
		foreach ($dispatch as &$d) {
			// $weight = 0;
			// foreach ($allgoods as $g) {
			// 	$weight+=$g['weight'] * $g['total'];
			// }
			$price = 0;
			if ($weight <= $d['firstweight']) {
				$price = $d['firstprice'];
			} else {
				$price = $d['firstprice'];
				$secondweight = $weight - $d['firstweight'];
				if ($secondweight % $d['secondweight'] == 0) {
					$price+= (int) ( $secondweight / $d['secondweight'] ) * $d['secondprice'];
				} else {
					$price+= (int) ( $secondweight / $d['secondweight'] + 1 ) * $d['secondprice'];
				}
			}
			$d['price'] = $price;
		}
		unset($d);
		if (checksubmit('submit')) {
			//是否自提
			$sendtype=1;

			//商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				if ($item['stock'] != -1 && $row['total'] > $item['stock']) {
					message('抱歉，“' . $row['title'] . '”此商品库存不足！', $this->createMobileUrl('store',array('op' => 'confirm')), 'error');
				}
				$goodsprice+= $row['totalprice'];
			}
			//运费
			$dispatchid = intval($_GPC['dispatch']);
			$dispatchprice = 0;
			foreach ($dispatch as $d) {
				if ($d['id'] == $dispatchid) {
					$dispatchprice = $d['price'];
					$sendtype = $d['dispatchtype'];
				}
			}
			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('YmdHi').random(10, 1),
				'price' => $goodsprice + $dispatchprice,
				'dispatchprice' => $dispatchprice,
				'goodsprice' => $goodsprice,
				'status' => 0,
				'sendtype' =>intval($sendtype),
				'dispatch' => $dispatchid,
				'goodstype' => intval($cart['type']),
				'remark' => $_GPC['remark'],
				'createtime' => TIMESTAMP,
				'regionid' => $member['regionid'],
				'type' => 'store',
				'uid' => $item['uid']
			);
			$order = pdo_fetch("SELECT id FROM".tablename('xcommunity_shopping_order')."WHERE ordersn=:ordersn",array(':ordersn' => $data['ordersn']));
			if ($ordersn) {
				message('订单已存在，无需提交',referer(),'error');
			}
			pdo_insert('xcommunity_shopping_order', $data);
			$orderid = pdo_insertid();
			//插入订单商品
			foreach ($allgoods as $row) {
				if (empty($row)) {
					continue;
				}
				$d = array(
					'uniacid' => $_W['uniacid'],
					'goodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $row['total'],
					'price' => $row['marketprice'],
					'createtime' => TIMESTAMP,
				
				);
				$o = pdo_fetch("select title from ".tablename('xiaofeng_store_goods_option')." where id=:id limit 1",array(":id"=>$row['optionid']));
				if(!empty($o)){
					$d['optionname'] = $o['title'];
				}
				pdo_insert('xiaofeng_store_order_goods', $d);
			}
			//清空购物车
			if (!$direct) {
				pdo_delete("xiaofeng_store_cart", array("uniacid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			//变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock1($orderid);
			}
			message('提交订单成功,现在跳转到付款页面...',$this->createMobileUrl('store', array('op' => 'pay','orderid' => $orderid)),'success');
		}
		$carttotal = $this->getCartTotal1();
		$profile = fans_search($_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
		include $this->template('store/confirm');
	}elseif ($op == 'pay') {
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('store',array('op' => 'myorder')), 'error');
		}
		// if (checksubmit('codsubmit')) {
		// 	$ordergoods = pdo_fetchall("SELECT goodsid, total,optionid FROM " . tablename('xiaofeng_store_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
		// 	if (!empty($ordergoods)) {
		// 		$goods = pdo_fetchall("SELECT id, title, thumb, marketprice, unit, total,credit FROM " . tablename('xiaofeng_store_goods') . " WHERE id IN ('" . implode("','", array_keys($ordergoods)) . "')");
		// 	}
		
		// 	pdo_update('xcommunity_shopping_order', array('status' => '1', 'paytype' => '3'), array('id' => $orderid));
		// 	message('订单提交成功，请您收到货时付款！', $this->createMobileUrl('store',array('op' => 'myorder')), 'success');
		// }
		if (checksubmit()) {
			if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
				message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'uniacid' => $_W['uniacid'])), 'error');
			}
			if ($order['price'] == '0') {
				$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
				exit;
			}
		}
		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('xiaofeng_store_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xiaofeng_store_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));

		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['title'] = $goodsTitle;
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		$this->pay($params);

	}elseif ($op == 'myorder') {
		if ($operation == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE id = :id AND from_user = :from_user AND type='store'", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('store',array('op' => 'myorder')), 'error');
			}
			pdo_update('xcommunity_shopping_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			message('确认收货完成！', $this->createMobileUrl('store',array('op' => 'myorder')), 'success');
		} else if ($operation == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' AND type='store' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('store',array('op' => 'myorder')), 'error');
			}
			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('xiaofeng_store_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total,o.optionid FROM " . tablename('xiaofeng_store_order_goods')
					. " o left join " . tablename('xiaofeng_store_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
			// foreach ($goods as &$g) {
				
			// 	$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xiaofeng_store_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
			// 	if ($option) {
			// 		$g['title'] = "[" . $option['title'] . "]" . $g['title'];
			// 		$g['marketprice'] = $option['marketprice'];
			// 	}
			// }
			// unset($g);
			$dispatch = pdo_fetch("select id,dispatchname from " . tablename('xiaofeng_store_dispatch') . " where id=:id limit 1", array(":id" => $item['dispatch']));
			include $this->template('store/order_detail');
		} else {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = intval($_GPC['status']);
			$where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND regionid='{$member['regionid']}'";
			if ($status == 2) {
				$where.=" and ( status=1 or status=2 )";
			} else {
				$where.=" and status=$status";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_shopping_order') . " WHERE $where AND type='store' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_shopping_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND type='store'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('xiaofeng_store_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total,o.optionid FROM " . tablename('xiaofeng_store_order_goods') . " o left join " . tablename('xiaofeng_store_goods') . " g on o.goodsid=g.id "
							. " WHERE o.orderid='{$row['id']}'");
					// foreach ($goods as &$item) {
					// 	//属性
					// 	$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xiaofeng_store_goods_option") . " where id=:id limit 1", array(":id" => $item['optionid']));
					// 	if ($option) {
					// 		$item['title'] = "[" . $option['title'] . "]" . $item['title'];
					// 		$item['marketprice'] = $option['marketprice'];
					// 	}
					// }
					// unset($item);
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
					$row['dispatch'] = pdo_fetch("select id,dispatchname from " . tablename('xiaofeng_store_dispatch') . " where id=:id limit 1", array(":id" => $row['dispatch']));
				}
			}
			include $this->template('store/order');
		}
	}























