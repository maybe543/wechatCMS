<?php

		$this->checkAuth();
        $totalprice = 0;
        $allgoods = array();

        $id = intval($_GPC['id']);
        $optionid = intval($_GPC['optionid']);
        $total = intval($_GPC['total']);
        if (empty($total)) {
            $total = 1;
        }
        $direct = false; //是否是直接购买
        $returnurl = ""; //当前连接
		$isfx = pdo_fetchcolumn("select flag from ".tablename('hc_hunxiao_member')." where weid = ".$weid." and from_user = '".$_W['openid']."'");
        if (!empty($id)) {
            $item = pdo_fetch("select id,thumb,ccate,title,weight, fxprice, marketprice,total,type,totalcnf,sales,unit,istime,timeend,issetfree from " . tablename("hc_hunxiao_goods") . " where id=:id limit 1", array(":id" => $id));

            if ($item['istime'] == 1) {
                if (time() > $item['timeend']) {
                    message('抱歉，商品限购时间已到，无法购买了！', referer(), "error");
                }
            }

            if (!empty($optionid)) {
                $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $optionid));
                if ($option) {
                    $item['optionid'] = $optionid;
                    $item['title'] = $item['title'];
                    $item['optionname'] = $option['title'];
                    $item['marketprice'] = $option['marketprice'];
                    $item['weight'] = $option['weight'];
                }
            }
            $item['stock'] = $item['total'];
            $item['total'] = $total;
            $item['totalprice'] = $total * $item['marketprice'];
            $allgoods[] = $item;
            $totalprice+= $item['totalprice'];
            if ($item['type'] == 1) {
                $needdispatch = true;
            }
            $direct = true;
            $returnurl = $this->createMobileUrl("confirm", array("id" => $id, "optionid" => $optionid, "total" => $total));
        }
        if (!$direct) {
            //如果不是直接购买（从购物车购买）
            $list = pdo_fetchall("SELECT * FROM " . tablename('hc_hunxiao_cart') . " WHERE  weid = '{$weid}' AND from_user = '{$_W['openid']}'");
            if (!empty($list)) {
                foreach ($list as &$g) {
                    $item = pdo_fetch("select id,thumb,ccate,title,weight,marketprice,total,type,totalcnf,sales,unit,issetfree from " . tablename("hc_hunxiao_goods") . " where id=:id limit 1", array(":id" => $g['goodsid']));
                    //属性
                    $option = pdo_fetch("select title,marketprice,weight,stock from " . tablename("hc_hunxiao_goods_option") . " where id=:id limit 1", array(":id" => $g['optionid']));
                    if ($option) {
                        $item['optionid'] = $g['optionid'];
                        $item['title'] = $item['title'];
                        $item['optionname'] = $option['title'];
                        $item['marketprice'] = $option['marketprice'];
                        $item['weight'] = $option['weight'];
                    }
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
            $returnurl = $this->createMobileUrl("confirm");
        }

        if (count($allgoods) <= 0) {
            header("location: " . $this->createMobileUrl('myorder'));
            exit();
        }
        //配送方式
        $dispatch = pdo_fetchall("select id,dispatchname,firstprice,firstweight,secondprice,secondweight from " . tablename("hc_hunxiao_dispatch") . " WHERE enabled = 1 and weid = {$weid} order by displayorder desc");
        $issetfree = 0;
		foreach ($dispatch as &$d) {

            $weight = 0;

            foreach ($allgoods as $g) {
                $weight+=$g['weight'] * $g['total'];
				if(empty($issetfree)){
					if(!empty($g['issetfree'])){
						$issetfree = $g['issetfree'];
					}
				}
            }

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
            $address = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE id = :id", array(':id' => intval($_GPC['address'])));
            if (empty($address)) {
                message('抱歉，请您填写收货地址！');
            }
            //商品价格
            $goodsprice = 0;
            foreach ($allgoods as $row) {
                if ($item['stock'] != -1 && $row['total'] > $item['stock']) {
                    message('抱歉，“' . $row['title'] . '”此商品库存不足！', $this->createMobileUrl('confirm'), 'error');
                }
                $goodsprice+= $row['totalprice'];
            }
            //运费
            $dispatchid = intval($_GPC['dispatch']);
            $dispatchprice = 0;
            foreach ($dispatch as $d) {
                if ($d['id'] == $dispatchid) {
                    $dispatchprice = $d['price'];
                }
            }			
			$shareid = 'hc_hunxiao_shareid'.$weid;				
            $data = array(
                'weid' => $weid,
                'from_user' => $_W['openid'],
                'ordersn' => date('md') . random(4, 1),
                'price' => $goodsprice + $dispatchprice,
                'dispatchprice' => $dispatchprice,
                'goodsprice' => $goodsprice,
                'status' => 0,
                'sendtype' => intval($_GPC['sendtype']),
                'dispatch' => $dispatchid,
                'paytype' => '2',
                'goodstype' => intval($cart['type']),
                'remark' => $_GPC['remark'],
                'addressid' => $address['id'],
                'createtime' => TIMESTAMP
            );
            pdo_insert('hc_hunxiao_order', $data);
            $orderid = pdo_insertid();
			$goodsinfo = pdo_fetchall("select id, credit from ".tablename('hc_hunxiao_goods')." where weid = ".$weid);
			$goodinfo = array();
			foreach($goodsinfo as $g){
				$goodinfo[$g['id']] = $g['credit'];
			}
			$mid = pdo_fetchcolumn("select id from ".tablename('hc_hunxiao_member')." where weid = ".$weid." and from_user = '".$_W['openid']."'");
            //插入订单商品
            foreach ($allgoods as $row) {
                if (empty($row)) {
                    continue;
                }
                $d = array(
                    'weid' => $weid,
                    'goodsid' => $row['id'],
                    'orderid' => $orderid,
                    'total' => $row['total'],
                    'price' => $row['marketprice'],
                    'createtime' => TIMESTAMP,
                    'optionid' => $row['optionid']
                );
                $o = pdo_fetch("select title from ".tablename('hc_hunxiao_goods_option')." where id=:id limit 1",array(":id"=>$row['optionid']));
                if(!empty($o)){
                    $d['optionname'] = $o['title'];
                }
				//获取商品id
				$ccate = $row['ccate'];
                pdo_insert('hc_hunxiao_order_goods', $d);
				if(intval($goodinfo[$row['id']])){
					if(intval($mid)){
						$credits = array(
							'weid'=>$weid,
							'mid'=>$mid,
							'orderid'=>$orderid,
							'goodsid' => $row['id'],
							'credit'=>$goodinfo[$row['id']],
							'flag'=>1,
							'status'=>-1,
							'total'=>$row['total'],
							'createtime'=>time()
						);
						pdo_insert('hc_hunxiao_credit', $credits);
					}	
				}
				
				$commission = pdo_fetchcolumn( " SELECT commission FROM ".tablename('hc_hunxiao_goods')."  WHERE id = ".$row['id']);
				if($commission == false || $commission == null || $commission <0){
					$commission = pdo_fetchcolumn("select globalCommission from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
				}
				$sharememberid = pdo_fetchcolumn("select shareid from ".tablename('hc_hunxiao_member')." where weid = ".$weid." and from_user = '".$_W['openid']."'");
				if(!empty($sharememberid)){
					$sharememid = $sharememberid;
				} else {
					$sharememid = intval($_COOKIE[$shareid]);
				}
				if(!empty($sharememid)){
					$comdef = pdo_fetchall("select * from ".tablename('hc_hunxiao_userdefault')." where weid = ".$weid);
					$comdefs = array();
					foreach($comdef as $c){
						$comdefs[$c['userdefault']] = $c['commission'];
					}
					$mr = array(
						'weid' => $weid,
						'goodsid' => $row['id'],
						'orderid' => $orderid,
						'total' => $row['total'],
						'createtime' => TIMESTAMP,
					);
					if(empty($comdef)){
						$mr['commission'] = $row['marketprice'] * $commission / 100;
						$mr['userdefault'] = 1;
						$mr['shareid'] = $sharememid;
						pdo_insert('hc_hunxiao_memberrelative', $mr);
					} else {
						$userdefaulttotal = pdo_fetchcolumn("select userdefault from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
						for($i=1; $i<=$userdefaulttotal; $i++){
							$mr['commission'] = $row['marketprice'] * $commission / 100 * $comdefs[$i] / 100;
							$mr['userdefault'] = $i;
							if($i==1){
								$mr['shareid'] = $sharememid;
								pdo_insert('hc_hunxiao_memberrelative', $mr);
							} elseif (!empty($sharememid)){
								$sharememid = pdo_fetchcolumn("select shareid from ".tablename('hc_hunxiao_member')." where id = ".$sharememid);
								if(!empty($sharememid)){
									$mr['shareid'] = $sharememid;
									pdo_insert('hc_hunxiao_memberrelative', $mr);
								} else {
									break;
								}
							}
						}
					}
				}
            }
            //清空购物车
            if (!$direct) {
                pdo_delete("hc_hunxiao_cart", array("weid" => $weid, "from_user" => $_W['openid']));
            }
            //$this->setCartGoods(array());
            //变更商品库存
            $this->setOrderStock($orderid);
			
            //  message('提交订单成功，现在跳转至付款页面...', $this->createMobileUrl('pay', array('orderid' => $orderid)), 'success');
			
            die("<script>alert('提交订单成功,现在跳转到付款页面...');location.href='" . $this->createMobileUrl('pay', array('orderid' => $orderid)) . "';</script>");
        }
        $carttotal = $this->getCartTotal();
        //$profile = fans_search($_W['openid'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
        $row = pdo_fetch("SELECT * FROM " . tablename('hc_hunxiao_address') . " WHERE isdefault = 1 and openid = :openid limit 1", array(':openid' => $_W['openid']));
        include $this->template('confirm');
?>