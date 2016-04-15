<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端小区超市
 */
	
	global $_GPC,$_W;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'list';
	//判断是否注册，只有注册后，才能进入
	$member = $this->changemember();
	$region = $this->mreg();
	if($op == 'list'){
		//获取购物车数量
		$carttotal = $this->getCartTotal();
		// $pindex = max(1, intval($_GPC["page"]));
		// $psize = 10;
		// $condition = " ";
		// $pcate = intval($_GPC['pcate']);
		// if ($pcate) {
		// 	$condition .="AND pcate=:pcate";
		// 	$params[':pcate'] = $pcate;
		// }
		// $category = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY displayorder DESC", array(), 'id');

		// $list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_goods') . " WHERE weid = '{$_W['uniacid']}' AND status = '1' $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			$pcate = intval($_GPC['pcate']);
			if ($pcate) {
				$condition .=" AND pcate =:pcate";
				$params[':pcate'] = $pcate; 
			}
			if (!empty($_GPC['keywords'])) {
				$condition .= " AND title LIKE '%{$_GPC['keywords']}%'";
			}
			$list = pdo_fetchall('SELECT * FROM'.tablename('xcommunity_goods')."WHERE weid='{$_W['weid']}' AND status = 1 AND type = 1 $condition order by createtime desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_goods')."WHERE weid='{$_W['weid']}' AND status = 1 AND type = 1 $condition order by createtime desc",$params);
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('shopping',array('op' => 'detail','id' => $value['id']));
				$thumb = tomedia($value['thumb']);
				$datetime = date('Y-m-d H:i',$value['createtime']);
				
				$data[]['html'] = "<li>
						                <a href=".$url." class='p-img'><img src='".$thumb ."'>
						                    <h3 class='p-name' style='font-size:12px;'>".$value['title']."<span style='font-size:12px;'>&nbsp;&nbsp;&nbsp;".$value['unit']."</span></h3></a>
						                <div class='channer_media' onclick='goodView.clickBuyGoodbyGoodPkno(".$value['id'].")'>
						                    <div class='p-price' style='font-size:12px;'>
						                    <span class='p-price-now'><b style='font-size:12px;'>¥ ".$value['marketprice']."</b></span>
						                    <span id='market_price' class='p-price-cost'><b style='font-size:12px;'>".$value['productprice']."</b></span>
						                    <i></i>

						                    </div>
						                </div>
						                <div class='tag'></div>
						                <div class='tag-tltle'>热卖</div>
						            </li>";
			}
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}
		//获取购物车数量
		$carttotal = $this->getCartTotal();
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/shopping/list');exit();
		}
	}elseif ($op == 'detail') {
		//商品详情
		$goodsid = intval($_GPC['id']);
		$goods = pdo_fetch("SELECT * FROM " . tablename('xcommunity_goods') . " WHERE id = :id", array(':id' => $goodsid));
		if (empty($goods)) {
			message('抱歉，商品不存在或是已经被删除！');
		}
		//展示多图
		$thumbs = unserialize($goods['thumb_url']);
		$piclist = array();
		foreach ($thumbs as $key => $value) {
			$piclist[] = tomedia($value);
		}

		$marketprice = $goods['marketprice'];
		$productprice= $goods['productprice'];
		$stock = $goods['total'];
		//获取购物车数量
		$carttotal = $this->getCartTotal();

		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/shopping/detail');exit();
		}
	}elseif ($op == 'mycart') {
		//添加到购物车
		if ($operation == 'add') {
			$goodsid = intval($_GPC['id']);
			$total = intval($_GPC['total']);
			$total = empty($total) ? 1 : $total;
			$goods = pdo_fetch("SELECT id,total,marketprice FROM " . tablename('xcommunity_goods') . " WHERE id = :id", array(':id' => $goodsid));
			if (empty($goods)) {
				$result['message'] = '抱歉，该商品不存在或是已经被删除！';
				message($result, '', 'ajax');
			}
			$marketprice = $goods['marketprice'];

			$row = pdo_fetch("SELECT id, total FROM " . tablename('xcommunity_cart') . " WHERE from_user = :from_user AND weid = '{$_W['uniacid']}' AND goodsid = :goodsid  ", array(':from_user' => $_W['fans']['from_user'], ':goodsid' => $goodsid));
			if ($row) {
				//判断是否超过库存
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
					'weid' => $_W['uniacid'],
					'goodsid' => $goodsid,
					'marketprice' => $marketprice,
					'from_user' => $_W['fans']['from_user'],
					'total' => $total,

				);
				pdo_insert('xcommunity_cart', $data);
			} else {
				//累加最多限制购买数量
				$t = $total + $row['total'];
				// if (!empty($goods['maxbuy'])) {
				// 	if ($t > $goods['maxbuy']) {
				// 		$t = $goods['maxbuy'];
				// 	}
				// }
				//存在
				$data = array(
					'marketprice' => $marketprice,
					'total' => $t,
				);
				pdo_update('xcommunity_cart', $data, array('id' => $row['id']));
			}
			//获取购物车数量
			$carttotal = $this->getCartTotal();
			$result = array(
				'result' => 1,
				'total' => $carttotal
			);

			die(json_encode($result));
		}elseif ($operation == 'remove') {
			//删除购物车中商品
				
				$cartids = explode(',',$_GPC['cartids']);
				if (!empty($cartids)) {
					foreach ($cartids as $key => $cartid) {
						pdo_delete('xcommunity_cart',array('id' => $cartid));
					}
				}
				die(json_encode(array("result" => 1)));
			
			exit();
		}elseif ($operation == 'update') {
			$id = intval($_GPC['id']);
			if (empty($id)) {
				message('缺少参数',referer(),'error');
			}
			$num = intval($_GPC['num']);
			$good = pdo_fetch("SELECT c.total as ctotal ,g.total as gtotal,c.goodsid as goodsid FROM".tablename('xcommunity_cart')."as c left join".tablename('xcommunity_goods')."as g on c.goodsid = g.id WHERE c.id=:id",array(':id' => $id));

			if ($good) {
				$t = $num;
				if ($t == $good['gtotal'] || $t < $good['gtotal']) {
					$sql = "update " . tablename('xcommunity_cart') . " set total=$num where id=:id";
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
			//显示购物车中商品
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			$totalprice = 0;
			if (!empty($list)) {
				foreach ($list as &$item) {
					$goods = pdo_fetch("SELECT  id,title, thumb, marketprice, unit, total,productprice FROM " . tablename('xcommunity_goods') . " WHERE id=:id limit 1", array(":id" => $item['goodsid']));
					$item['goods'] = $goods;
					$item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
					$totalprice += $item['totalprice'];
				}
				unset($item);
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/shopping/cart');exit();
			}
		}
		
	}elseif ($op == 'confirm') {
		
		$totalprice = 0;
		//结算商品信息
		$allgoods = array();
		//商品id
		$id = intval($_GPC['id']);
		//购买商品数量
		$total = intval($_GPC['total']);
		if ( (empty($total)) || ($total < 1) ) {
			$total = 1;
		}
		$direct = false; //是否是直接购买
		$returnurl = ""; //当前连接
		//获取当前用户的信息
		// $member = mc_fetch($_W['fans']['uid'],array('mobile','address','realname'));
		if (!empty($id)) {
			//商品信息
			$item = pdo_fetch("select * from " . tablename("xcommunity_goods") . " where id=:id limit 1", array(":id" => $id));

			$item['stock'] = $item['total'];
			$item['total'] = $total;
			$item['totalprice'] = $total * $item['marketprice'];
			$allgoods[] = $item;
			$totalprice+= $item['totalprice'];
			if ($item['type'] == 1) {
				$needdispatch = true;
			}
			$direct = true;
			$returnurl = $this->createMobileUrl("shopping", array('op' => 'confirm',"id" => $id,"total" => $total));
		}
		if (!$direct) {
			//如果不是直接购买（从购物车购买）
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_cart') . " WHERE  weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}'");
			if (!empty($list)) {
				foreach ($list as &$g) {
					$item = pdo_fetch("select * from " . tablename("xcommunity_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
					//属性
					
					$item['stock'] = $item['total'];
					$item['total'] = $g['total'];
					$item['totalprice'] = $g['total'] * $item['marketprice'];
					$allgoods[] = $item;
					$totalprice+= $item['totalprice'];
				}
				unset($g);
			}
			$returnurl = $this->createMobileUrl("shopping",array('op' => 'confirm'));
		}
		if (count($allgoods) <= 0) {
			header("location: " . $this->createMobileUrl('shopping' ,array('op' => 'myorder')));
			exit();
		}
		unset($d);
		
		if (checksubmit('submit')) {
		

			//商品价格
			$goodsprice = 0;
			foreach ($allgoods as $row) {
				if ($item['stock'] != -1 && $row['total'] > $item['stock']) {
					message('抱歉，“' . $row['title'] . '”此商品库存不足！', $this->createMobileUrl('shopping',array('op' => 'confirm')), 'error');
				}
				$goodsprice+= $row['totalprice'];
			}

			$data = array(
				'weid' => $_W['uniacid'],
				'from_user' => $_W['fans']['from_user'],
				'ordersn' => date('YmdHi').random(10, 1),
				'price' => $goodsprice,
				'goodsprice' => $goodsprice,
				'status' => 0,
				'remark' => $_GPC['remark'],
				'createtime' => TIMESTAMP,
				'regionid' => $member['regionid'],
				'type' => 'shopping',
			);
			if ($item['uid']) {
					$data['uid'] = $item['uid'];
				}
			$order = pdo_fetch("SELECT id FROM".tablename('xcommunity_order')."WHERE ordersn=:ordersn",array(':ordersn' => $data['ordersn']));
			if ($order) {
				message('订单已存在，无需提交',referer(),'error');
			}
			pdo_insert('xcommunity_order', $data);
			$orderid = pdo_insertid();
			//插入订单商品
			foreach ($allgoods as $row) {
				if (empty($row)) {
					continue;
				}
				$d = array(
					'weid' => $_W['uniacid'],
					'goodsid' => $row['id'],
					'orderid' => $orderid,
					'total' => $row['total'],
					'price' => $row['marketprice'],
					'createtime' => TIMESTAMP,

				);

				pdo_insert('xcommunity_order_goods', $d);
			}
			//清空购物车
			if (!$direct) {
				pdo_delete("xcommunity_cart", array("weid" => $_W['uniacid'], "from_user" => $_W['fans']['from_user']));
			}
			//变更商品库存
			if (empty($item['totalcnf'])) {
				$this->setOrderStock($orderid);
			}
			header("location: " . $this->createMobileUrl('shopping', array('op' => 'pay','orderid' => $orderid)));
			//message('提交订单成功,现在跳转到付款页面...',$this->createMobileUrl('shopping', array('op' => 'pay','orderid' => $orderid)),'success');
		}

		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/shopping/confirm');exit();
		}
	}elseif ($op == 'pay') {
		//查超市支持的支付方式
		$setdata = $this->syspay(1);
		$set = unserialize($setdata['pay']);
		//查当前订单信息
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
		}

		// 商品编号
		$sql = 'SELECT `goodsid` FROM ' . tablename('xcommunity_order_goods') . " WHERE `orderid` = :orderid";
		$goodsId = pdo_fetchcolumn($sql, array(':orderid' => $orderid));
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xcommunity_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $goodsId));
		// if (checksubmit()) {
		// 	if ($order['paytype'] == 1 && $_W['fans']['credit2'] < $order['price']) {
		// 		message('抱歉，您帐户的余额不够支付该订单，请充值！', create_url('mobile/module/charge', array('name' => 'member', 'weid' => $_W['uniacid'])), 'error');
		// 	}
		// 	if ($order['price'] == '0') {
		// 		$this->payResult(array('tid' => $orderid, 'from' => 'return', 'type' => 'credit2'));
		// 		exit;
		// 	}
		// }
		//$params['tid'] = $orderid;
		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		$params['module'] = 'xfeng_community';
		$params['title'] = $goodsTitle;
	
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/shopping/pay');exit();
		}
	}elseif ($op == 'myorder') {
		if ($operation == 'confirm') {
			$orderid = intval($_GPC['orderid']);
			$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id AND from_user = :from_user AND type='shopping' ", array(':id' => $orderid, ':from_user' => $_W['fans']['from_user']));
			if (empty($order)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
			}
			pdo_update('xcommunity_order', array('status' => 3), array('id' => $orderid, 'from_user' => $_W['fans']['from_user']));
			message('确认收货完成！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'success');
		} else if ($operation == 'detail') {
			$orderid = intval($_GPC['orderid']);
			$item = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' and id='{$orderid}' AND type='shopping' limit 1");
			if (empty($item)) {
				message('抱歉，您的订单不存或是已经被取消！', $this->createMobileUrl('shopping',array('op' => 'myorder')), 'error');
			}
			$goodsid = pdo_fetch("SELECT goodsid,total FROM " . tablename('xcommunity_order_goods') . " WHERE orderid = '{$orderid}'", array(), 'goodsid');
			$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice, o.total FROM " . tablename('xcommunity_order_goods')
					. " o left join " . tablename('xcommunity_goods') . " g on o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
			// foreach ($goods as &$g) {
			// 	//属性
			// 	$option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("xcommunity_shopping_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
			// 	if ($option) {
			// 		$g['title'] = "[" . $option['title'] . "]" . $g['title'];
			// 		$g['marketprice'] = $option['marketprice'];
			// 	}
			// }
			// unset($g);

			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/shopping/order_detail');exit();
			}
		} elseif($operation == 'list') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$status = intval($_GPC['status']);
			$where = " weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' ";
			if ($status == 2) {
				$where.=" and ( status=1 or status=2 )";
			} else {
				$where.=" and status=$status";
			}
			$list = pdo_fetchall("SELECT * FROM " . tablename('xcommunity_order') . " WHERE $where AND type='shopping' ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), 'id');
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('xcommunity_order') . " WHERE weid = '{$_W['uniacid']}' AND from_user = '{$_W['fans']['from_user']}' AND type='shopping'");
			$pager = pagination($total, $pindex, $psize);
			if (!empty($list)) {
				foreach ($list as &$row) {
					$goodsid = pdo_fetchall("SELECT goodsid,total FROM " . tablename('xcommunity_order_goods') . " WHERE orderid = '{$row['id']}'", array(), 'goodsid');
					$goods = pdo_fetchall("SELECT g.id, g.title, g.thumb, g.unit, g.marketprice,o.total FROM " . tablename('xcommunity_order_goods') . " o left join " . tablename('xcommunity_goods') . " g on o.goodsid=g.id "
							. " WHERE o.orderid='{$row['id']}'");
					
					$row['goods'] = $goods;
					$row['total'] = $goodsid;
				}
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/shopping/order');exit();
			}
		}
	}elseif ($op == 'category') {
			//商品分类
			$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE type=5 AND weid=:weid",array(':weid' => $_W['weid']));

			$data = array();
			foreach ($categories as $key => $value) {
				$url = $this->createMobileUrl('shopping',array('op' => 'list','pcate' => $value['id']));
				$thumb = tomedia($value['thumb']);
				$data[]['html'] = "
						<li>
                            <div>
                                <a href='".$url."'>
                                <img class='cate_icon_img' src='".$thumb."'>
                                <span style='font-size:12px;'>".$value['name']."</span>
                                </a>
                            </div>
                        </li>";
			}
			$r = array(
		    		'allhtml' => $data,		    		
		    	);

		   print_r(json_encode($r));exit();
	
	}























