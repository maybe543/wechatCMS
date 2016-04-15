<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 独立商家
 */


	global $_W,$_GPC;
	$region = $this->mreg();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'list';
	WeSession::start($_W['uniacid'],$_W['fans']['from_user'],600);
	if($_GPC['lng']&&$_GPC['lat']){
		$_SESSION['lng'] = $_GPC['lng'];
		$_SESSION['lat'] = $_GPC['lat'];
	}
	$lng = $_SESSION['lng'] ? $_SESSION['lng'] : $_GPC['lng'];
	$lat = $_SESSION['lat'] ? $_SESSION['lat'] : $_GPC['lat'];
	if($op == 'list' || $op == 'search'){
		//微信端商家展示
		
		if ($_W['isajax'] || $_W['ispost']) {
			// if ($lng && $lat) {
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 10;
				$settings = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				if ($settings['range']) {
					$range = $settings['range'];
				}else{
					$range = 5;
				}
				
				$point = $this->squarePoint($lng, $lat, $range);
				$condition = '';
				if ($lng&&$lat) {
					$condition .=" AND lat<>0 AND lat >= '{$point['right-bottom']['lat']}' AND lat <= '{$point['left-top']['lat']}' AND lng >= '{$point['left-top']['lng']}' AND lng <= '{$point['right-bottom']['lng']}'";

				}
		        $keyword = $_GPC['keyword'];
		        if ($keyword) {
		        	$condition .= " AND sjname LIKE '%{$_GPC['keyword']}%'";
		        }
		        $parent = $_GPC['parent'];
		        if ($parent) {
		        	$condition .= " AND parent = '{$parent}'";
		        }
		        $sql = "SELECT * FROM".tablename('xcommunity_dp')."WHERE weid='{$_W['weid']}' $condition order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		       // echo $_GPC['parent'];
		        // print_r($sql);
				$result = pdo_fetchall($sql);
				// print_r($result);
				$count = count($result);
				$list = array();
				if (!empty($result)) {
			            $min = -1;
			            foreach ($result as &$row) {
			                $row['distance'] = $this->getDistance($lat, $lng, $row['lat'], $row['lng']);
			                if ($min < 0 || $row['distance'] < $min) {
			                    $min = $row['distance'];
			                }
			            }
			            // echo $row['distance'];
			            unset($row);

			            $temp = array();
			            for ($i = 0; $i < $count; $i++) {
			                foreach ($result as $j => $row) {
			                    if (empty($temp['distance']) || $row['distance'] < $temp['distance']) {
			                        $temp = $row;
			                        $h = $j;
			                    }
			                }
			                if (!empty($temp)) {
			                    $juli = floor($temp['distance'])/1000;
			                    $list[] = array(
			                        'sjname' => $temp['sjname'],
			                        'juli'  => sprintf('%.1f', (float)$juli),
			                        'lng'   => $temp['lng'],
			                        'lat'   => $temp['lat'],
			                        'address'=>$temp['address'],
			                        'mobile' => $temp['picurl'],
			                        'picurl' => $temp['picurl'],
			                        'id' => $temp['id'],
			                    );
			                    unset($result[$h]);
			                    $temp = array();
			                }
			            }
			            $html = '';
						foreach ($list as $key => $value) {
							$thumb = tomedia($value['picurl']);
							$url = $this->createMobileUrl('business',array('op' => 'detail','id' => $value['id']));
							$html .="
									<div class=\"list-box\">
					                    <div class=\"list-img\">
					                        <a class=\"pic\" href=\"".$url."\"><img src=\"".$thumb."\"></a>
					                    </div>
					                    <div class=\"list-content\">
					                        <p>
					                            <a class=\"fl\" title=\"".$value['sjname']."\" href=\"".$url."\">".$value['sjname']."</a>
					                            <span class=\"range fr\">".$value['juli']."km</span>
					                        </p>
					                        <div class=\"clear\"></div>
					                        <p class=\"c_h overflow_clear\">".$value['address']."</p>
					                        <div class=\"listBox_tag_box\"></div>
					                        <a href=\"tel:".$value['mobile']."\">
					                            <div class=\"seller_tel_btn\"></div>
					                        </a>
					                    </div>
					                </div>

							";
						}
						print_r($html);exit();
			            
			        } else {
			            return 0;
			        }
				
			// }
		}

		if ($op == 'list') {
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/list');exit();
			}
		}
		if ($op == 'search') {
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/search');exit();
			}
		}
		
	}elseif ($op == 'detail') {
		//微信端商家内容页
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_dp')."WHERE id=:id",array(':id' => $id));
			$thumb = tomedia($item['picurl']);
			if ($item['id']) {
				$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND type = 2 AND dpid = :dpid AND isrecommand = 1 ",array(':weid' => $_W['uniacid'],':dpid' => $item['id']));
			}
			$count = pdo_fetchcolumn("SELECT count(*) FROM".tablename('xcommunity_rank')."WHERE weid=:weid AND dpid=:dpid ",array(':weid' => $_W['uniacid'],':dpid' => $id));
			// $rank = pdo_fetch("SELECT * FROM".tablename('xcommunity_rank')."WHERE weid=:weid AND dpid=:dpid ",array(':weid' => $_W['uniacid'],':dpid' => $id));
			// print_r(unserialize($rank['content']));exit();
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/business/detail');exit();
		}
	}elseif ($op == 'coupon') {
		//团购券

		if ($operation == 'list') {
			//团购券列表
			$dpid = intval($_GPC['dpid']);

			if ($dpid) {
				$dp = pdo_fetch("SELECT sjname FROM".tablename('xcommunity_dp')."WHERE weid=:weid AND id=:id",array(':weid' => $_W['uniacid'],':id' => $dpid));

			}
			if ($_W['isajax'] || $_W['ispost']) {
		
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 10;
							// echo $dpid;exit();
		        $sql = "SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND  type = 2 AND dpid = :dpid order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		        $params[':weid'] = $_W['uniacid'];
		        $params[':dpid'] = $dpid;
				$result = pdo_fetchall($sql,$params);
				// print_r($result);exit();

				$html = '';
				foreach ($result as $key => $value) {
					$thumb = tomedia($value['thumb']);
					$url = $this->createMobileUrl('business',array('op' => 'coupon','operation' => 'detail','gid' => $value['id']));
					$price = $value['productprice'] - $value['marketprice'];
					$content = cutstr($value['content'],30);
					$html .="
							<li>
		                        <div class=\"fl\">
		                            <div class=\"img\">
		                                <a href=\"".$url."\"><img src=\"".$thumb."\">
		                                    <div class=\"bq\"><em class=\"ico_1\"></em></div>
		                                </a>
		                            </div>
		                            <h4 class=\"overflow_clear\"><a href=\"".$url."\">".$value['title']."</a></h4>
		                            <p class=\"black9\">".$content."</p>
		                            <p class=\"price fontcl1\">￥".$value['marketprice']."
		                                <del>￥".$value['productprice']."</del>
		                            </p>
		                        </div>
		                        <div class=\"fr\"><a href=\"".$url."\" class=\"btn\">立省".$price."元</a></div>
		                        <div class=\"clear\"></div>
		                    </li>

					";
				}
				print_r($html);exit();

			}

			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/coupon/list');exit();
			}
		}elseif ($operation == 'detail') {
			//团购券详情
			$gid = intval($_GPC['gid']);
			if ($gid) {
				$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND id=:gid",array(':weid' => $_W['uniacid'],':gid' => $gid));
				if ($item) {

					$dp = pdo_fetch("SELECT * FROM".tablename('xcommunity_dp')."WHERE weid=:weid AND id=:dpid",array(':weid' => $_W['weid'],':dpid' => $item['dpid']));
					
					$distance= $this->getDistance($lat, $lng, $dp['lat'], $dp['lng']);
					$juli = floor($distance)/1000;
					$dp['distance'] = $juli;
				}
				$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_goods')."WHERE weid=:weid AND dpid=:dpid ",array(':weid' => $_W['weid'],':dpid' => $item['dpid']));
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/coupon/detail');exit();
			}
		}elseif ($operation == 'confirm') {
			//支付
			$gid = intval($_GPC['gid']);
			if ($gid) {
				$item = pdo_fetch("select * from " . tablename("xcommunity_goods") . " where id=:id limit 1", array(":id" => $gid));
			}
			$dpid = intval($_GPC['dpid']);
			if ($dpid) {
				$dp = pdo_fetch("SELECT uid FROM".tablename('xcommunity_dp')."WHERE weid=:weid AND id=:id",array(':weid' => $_W['uniacid'],':id' => $dpid));
			}
			if ($_W['ispost']) {
				$data = array(
					'weid' => $_W['uniacid'],
					'from_user' => $_W['fans']['from_user'],
					'ordersn' => date('YmdHi').random(10, 1),
					'price' => $_GPC['price'],
					'gid' => $gid,
					'status' => 0,
					'createtime' => TIMESTAMP,
					'type' => 'business',
					'num' => intval($_GPC['num']),
					'goodsprice' => $_GPC['price'],
					'enable' => 1
				);
				if ($dp['uid']) {
					$data['uid'] = $dp['uid'];
				}
				// print_r($data);exit();
				$order = pdo_fetch("SELECT id FROM".tablename('xcommunity_order')."WHERE ordersn=:ordersn",array(':ordersn' => $data['ordersn']));
				if ($order) {
					message('订单已存在，无需提交',referer(),'error');
				}
				pdo_insert('xcommunity_order', $data);
				$orderid = pdo_insertid();
				header("location: " . $this->createMobileUrl('business', array('op' => 'pay','orderid' => $orderid)));
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/coupon/confirm');exit();
			}
		}elseif ($operation == 'my') {
			$status = !empty($_GPC['status']) ? $_GPC['status'] : 0;
			if ($_W['isajax'] || $_W['ispost']) {
		
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 10;
		        $sql = "SELECT o.*,g.title as title ,g.thumb as thumb FROM".tablename('xcommunity_order')."as o left join".tablename('xcommunity_goods')."as g on o.gid = g.id WHERE o.weid=:weid AND  o.type = 'business' AND o.from_user = :from_user AND o.status = :status order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		        $params[':weid'] = $_W['uniacid'];
		        $params[':from_user'] = $_W['fans']['from_user'];
		        $params[':status'] = $status;
				$result = pdo_fetchall($sql,$params);
				// print_r($result);exit();

				$html = '';
				foreach ($result as $key => $value) {
					$thumb = tomedia($value['thumb']);
					$url = $this->createMobileUrl('business',array('op' => 'coupon','operation' => 'detail','gid' => $value['gid']));
					$link = $this->createMobileUrl('business',array('op' => 'pay','orderid' => $value['id']));
					$html .="
							<div class=\"list-box\">
			                    <div class=\"list-img\">
			                        <a href=\"".$url."\"><img src=\"".$thumb."\"></a>
			                    </div>
			                    <div class=\"buy-content\">
			                        <p class=\"\"><span class=\"fl overflow_clear\" style=\"width:60%;\"><a href=\"".$url."\">".$value['title']."</a></span><span class=\"fr\"><a class=\"order_detail\" href=\"".$url."\">详情</a></span></p>
			                        <p><span class=\"spread-money\">总价：￥".$value['price']."</span><span class=\"spread-money2\">数量：".$value['num']."</span></p>
			                        <p class=\"spread-for\">";
			                        if ($value['status']) {
			                        	$html .="已付款";
			                        }else{

			                           $html .="未付款";
			                        }

			                      $html.="</p>";
			                      if (empty($value['status'])) {
			                      	$html.="     <a style=\"color:#fff;\" href=\"\" class=\"button\">取消</a>
			                        <a style=\"color:#fff;\" href=\"".$link."\" class=\"button2\">付款</a>";
			                      }
			                   
			            $html.="</div>
			                </div>

					";
				}
				print_r($html);exit();

			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/coupon/my');exit();
			}
		}
	}elseif ($op == 'rank') {
		//商家评价
		$dpid = intval($_GPC['dpid']);

		if ($dpid) {
			$dp = pdo_fetch("SELECT * FROM".tablename('xcommunity_dp')."WHERE weid=:weid AND id=:id",array(':weid' => $_W['uniacid'],':id' => $dpid));

		}
		if ($operation == 'add') {
			$rank = pdo_fetch("SELECT * FROM".tablename('xcommunity_rank')."WHERE weid=:weid AND dpid=:dpid AND openid=:openid",array(':weid' =>$_W['uniacid'],':dpid' => $dpid,':openid' => $_W['fans']['from_user']));

			if ($_W['ispost']) {
				$data = array(
						'weid' => $_W['uniacid'],
						'type' => 1,
						'dpid' => $dpid,
						'openid' => $_W['fans']['from_user'],
						'content' => serialize($_GPC['data']), 
						'createtime' => TIMESTAMP

					);
				$r = pdo_insert('xcommunity_rank',$data);
				
				if ($r) {
					echo "<script language='javascript'>";
					echo "  alert('评价成功');";
					// echo "  window.location='xxx.php';";
					echo "</script>";
				}
			}
			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($rank) {
				if ($styleid) {
					include $this->template('style/style'.$styleid.'/business/rank/rank');exit();
				}
			}else{

				if ($styleid) {
					include $this->template('style/style'.$styleid.'/business/rank/add');exit();
				}
			}
			
		}elseif ($operation == 'list') {
			if ($_W['isajax'] || $_W['ispost']) {
		
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 10;
							// echo $dpid;exit();
		        $sql = "SELECT * FROM".tablename('xcommunity_rank')."WHERE weid=:weid AND  type = 1 AND dpid = :dpid order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
		        $params[':weid'] = $_W['uniacid'];
		        $params[':dpid'] = $dpid;
				$result = pdo_fetchall($sql,$params);
				// print_r($result);exit();

				$html = '';
				foreach ($result as $key => $value) {
					$member = $this->member($value['openid']);
					$c = unserialize($value['content']);
					// print_r($c);
					load()->model('mc');
					$m =  mc_fansinfo($value['openid']);
					$html .="
							<div class=\"guest-box\">
		                        <div class=\"icon\"><img src=\"".$m['tag']['avatar']."\"></div>
		                        <div class=\"guest-box-content\">
		                            <p>".$member['realname']."<span>2016-02-21</span></p>
		                            <div class=\"point-star\">
		                                <div style=\"width: 0.9rem;\"></div>
		                            </div>
		                            <p class=\"c_h\">".$c['contents']."</p>
		                            <p class=\"img\">
		                            </p>
		                        </div>
		                    </div>

					";
				}
				print_r($html);exit();

			}

			$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
			if ($styleid) {
				include $this->template('style/style'.$styleid.'/business/rank/list');exit();
			}
		}
	}elseif ($op == 'pay') {
		//查商家支持的支付方式
		$setdata = $this->syspay(3);
		$set = unserialize($setdata['pay']);
		//查当前订单信息
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', referer(), 'error');
		}
		// 商品名称
		$sql = 'SELECT `title` FROM ' . tablename('xcommunity_goods') . " WHERE `id` = :id";
		$goodsTitle = pdo_fetchcolumn($sql, array(':id' => $order['gid']));
		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['goodsprice'];
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		$params['module'] = 'xfeng_community';
		$params['title'] = $goodsTitle;
	
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/business/pay');exit();
		}
	}














