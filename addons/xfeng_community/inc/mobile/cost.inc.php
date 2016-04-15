<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端小区活动
 */

	
	global $_GPC,$_W;

	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	//判断是否注册，只有注册后，才能进入
	$member = $this->changemember();
	$region = $this->mreg();
	$m = mc_fetch($_W['fans']['uid'],array('mobile','address','realname'));
	$id = intval($_GPC['id']);
	if($op == 'list'){
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 10;
			$condition = '';
			$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_cost_list')."WHERE weid='{$_W['weid']}' AND homenumber ='{$member['address']}' AND regionid ='{$member['regionid']}' order by createtime desc LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_cost_list')."WHERE weid='{$_W['weid']}' AND homenumber ='{$member['address']}' AND regionid ='{$member['regionid']}' order by createtime desc");

			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('cost',array('op' => 'detail','id' => $value['id'],'cid' => $value['cid']));
				
				$data[]['html'] = "
					
						 <div class='weui_cells weui_cells_access'>
		                    <a class='weui_cell' href='".$url."'>
		                        <div class='weui_cell_bd weui_cell_primary'>
		                            <p style='font-size:12px;width:75%'>".$value['costtime']."物业账单合计".$value['total']."元</p>
		                        </div>
		                        <div class='weui_cell_ft'>";
		                        if ($value['status'] == '否') {
		                        	$data[]['html'] .="<span class='label label-success'>支付</span>";
		                        }else{
		                        	$data[]['html'] .="<span class='label label-default'>已支付</span>";
		                        }
		            $data[]['html'].="            </div>
		                    </a>
		                </div>
					
				";
			}
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/cost/list');exit();
		}
	}elseif($op == 'detail'){
		$cid = intval($_GPC['cid']);
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_cost_list')."WHERE weid=:weid AND id=:id",array(':weid' => $_W['weid'],':id' => $id));
		if (empty($item)) {
			message('费用不存在或已被删除',referer(),'error');
		}
		if (checksubmit('submit')) {
			$data = array(
					'weid' => $_W['uniacid'],
					'from_user' => $_W['fans']['from_user'],
					'ordersn' => date('YmdHi').random(10, 1),
					'createtime' => TIMESTAMP,
					'price'	=> $item['total'],
					'pid' => $id,
					'type' => 'pfree',
					'regionid' => $member['regionid'],
				);

			$order = pdo_fetch("SELECT * FROM".tablename('xcommunity_order')."WHERE pid=:pid",array(':pid' => $id));
			// if ($order) {
			// 	message('订单已存在，无需提交',referer(),'error');
			// }
			// $o = pdo_fetch("SELECT * FROM".tablename('xcommunity_order')."WHERE pid=:pid",array(':pid' => $id));
			if (empty($order)) {
				pdo_insert('xcommunity_order', $data);
				$orderid =pdo_insertid();
			}
			
			//判断是否开启支付宝独立支付
			$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id=:id",array(':id' => $item['regionid']));
			if ($region['pid']) {
				$payment = pdo_fetch("SELECT * FROM".tablename('xcommunity_alipayment')."WHERE uniacid=:uniacid AND pid=:pid",array(':pid' => $region['pid'],':uniacid' => $_W['uniacid']));
			}
			
			if ($payment['account']&&$payment['partner']&&$payment['secret']) {
				$alipay = array(
					'switch' => 1,
					'account' => $payment['account'],
					'partner' => $payment['partner'],
					'secret' => $payment['secret'],
				);
				if ($order) {
					$ordersn = $order['ordersn'];
					$price = $order['price'];
				}else{
					$ordersn = $data['ordersn'];
					$price = $data['price'];
				}
				if(empty($log)) {
					$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => 'xfeng_community'));
					$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
					$fee = $params['fee'];
					$record = array();
					$record['uniacid'] = $_W['uniacid'];
					$record['openid'] = $_W['fans']['from_user'];
					$record['module'] = 'xfeng_community';
					$record['type'] = 'alipay';
					$record['tid'] = $ordersn;
					$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
					$record['fee'] = $price;
					$record['status'] = '0';
					$record['is_usecard'] = 0;
					$record['card_id'] = 0;
					$record['card_fee'] = $price;
					$record['encrypt_code'] = '';
					$record['acid'] = $_W['acid'];

					if(pdo_insert('core_paylog', $record)) {
						$plid = pdo_insertid();
						$record['plid'] = $plid;
						$log = $record;
					} else {
						message('系统错误, 请稍后重试.');
					}
				}
				
				if(!empty($plid)) {
					pdo_update('core_paylog', array('openid' => $_W['member']['uid']), array('plid' => $plid));
				}
				$params = array();
				$params['tid'] = $ordersn;
				$params['user'] = $_W['fans']['from_user'];
				$params['fee'] = $price;
				$params['title'] = '物业费支付';
				$params['uniontid'] = $log['uniontid'];
				$params['ordersn'] = $ordersn;
				$params['pid'] = $region['pid']; //物业id
				$params['cid'] = $cid;//物业费id
				load()->func('communication');
				$ret = $this->alipay_build($params, $alipay);
				if($ret['url']) {
					echo '<script type="text/javascript" src="../payment/alipay/ap.js"></script><script type="text/javascript">_AP.pay("'.$ret['url'].'")</script>';
					exit();
					
				}





			}else{
				if ($orderid) {
					header("location: " . $this->createMobileUrl('cost', array('op' => 'pay','orderid' => $orderid)));
				}

			}
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/cost/detail');exit();
		}
	}elseif ($op == 'pay') {
		//查物业费支持的支付方式
		$setdata = $this->syspay(2);
		$set = unserialize($setdata['pay']);
		//查当前订单信息
		$orderid = intval($_GPC['orderid']);
		$order = pdo_fetch("SELECT * FROM " . tablename('xcommunity_order') . " WHERE id = :id", array(':id' => $orderid));
		if ($order['status'] != '0') {
			message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', referer(), 'error');
		}
		$params['tid'] = $order['ordersn'];
		$params['user'] = $_W['fans']['from_user'];
		$params['fee'] = $order['price'];
		$params['ordersn'] = $order['ordersn'];
		$params['virtual'] = $order['goodstype'] == 2 ? true : false;
		$params['module'] = 'xfeng_community';
		$params['title'] = '物业费支付';
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/cost/pay');exit();
		}
	}









