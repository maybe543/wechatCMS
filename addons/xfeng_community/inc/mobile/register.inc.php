<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端注册页面
 */
	global $_GPC,$_W;
	$regions = $this->regions();
	if (empty($regions)) {
		message('该公众号还没有小区信息,请联系相关负责人添加小区',referer(),'error');
	}

	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'region';

	if ($op == 'member') {
		//判断有没有注册
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid=:openid",array(':openid' => $_W['fans']['from_user']));
		// if ($member) {
		// 	$dat = array(
		// 			'regionid' => intval($_GPC['regionid']),
		// 			'status' => 0
		// 		);
		// 	$result = pdo_update('xcommunity_member',$dat,array('id' => $member['id']));
		// 	if ($result) {
				
		// 		message('等待管理员审核。',referer(),'success');exit();
		// 	}
		// }
		//判断是否开启短信验证
		$sms = pdo_fetch("SELECT verifycode FROM".tablename('xcommunity_wechat_smsid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		//是否开启房号注册码验证
		$set = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		
		if($_W['isajax']){
			$code = $_GPC['code'];
			if ($code) {
				$room = pdo_fetch("SELECT room FROM".tablename('xcommunity_room')."WHERE uniacid=:uniacid AND code=:code",array(':uniacid' => $_W['uniacid'],':code' => $code));
				if ($room['room'] != $_GPC['address']) {
					$result = array(
							'status' => 4,
						);
					echo json_encode($result);exit();
				}
			}
			if ($set['room_status']) {
				if ($_GPC['mobile']) {
					$room = pdo_fetch("SELECT room FROM".tablename('xcommunity_room')."WHERE uniacid=:uniacid AND mobile=:mobile",array(':uniacid' => $_W['uniacid'],':mobile' => $_GPC['mobile']));
					if ($room['room'] != $_GPC['address']) {
						$result = array(
								'status' => 5,
							);
						echo json_encode($result);exit();
					}
				}
			}
			
			load()->model('mc');
			//$res = mc_check(array('mobile' => $_GPC['mobile']));
			if (!$member) {
				$res = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE mobile=:mobile AND  weid=:weid ",array(':mobile' => $_GPC['mobile'],':weid' => $_W['weid']));
				if ($res) {
					$result = array(
						'status' => 2,
					);
					echo json_encode($result);exit();
				}
			}

				$verifycode = intval($_GPC['verifycode']);	
				if ($verifycode) {
					if ($verifycode != $_SESSION['code']) {
						$result = array(
								'status' => 3,
							);
						echo json_encode($result);exit();
					}
				}
				if ($set['room_enable']) {
					if (empty($_GPC['address'])) {
						$result = array(
								'status' => 7,
							);
						echo json_encode($result);exit();
					}else{
						$address = $_GPC['address'];
					}
				}else{
					$region = $this->region($_GPC['regionid']);
					if (empty($_GPC['build']) || empty($_GPC['room'])) {
						$result = array(
								'status' => 8,
							);
						echo json_encode($result);exit();
					}
					if ($_GPC['unit']) {
						$address = $region['dist'].$region['title'].$_GPC['build'].'栋'.$_GPC['unit'].'单元'.$_GPC['room'].'室';
					}else{
						$address = $region['dist'].$region['title'].$_GPC['build'].'栋'.$_GPC['room'].'室';	
					}
					
				}

				if ($member) {
					$status = 0;
				}else{
					$status = 1;
 				}
				$data = array(
						'weid' => $_W['uniacid'],
						'createtime' => TIMESTAMP,
						'regionid' => intval($_GPC['regionid']),
						'status' => $status,
						'type' => intval($_GPC['type']),
						'remark' => $_GPC['remark'],
						'openid' => $_W['fans']['from_user'],
						'realname' => $_GPC['realname'],
						'mobile' => $_GPC['mobile'],
						'address' => $address,
					);
				$rs = mc_update($_W['fans']['uid'], array('mobile' => $_GPC['mobile'],'realname' => $_GPC['realname'],'address' => $_GPC['address']));
				if ($rs) {
					$data['memberid'] = $_W['member']['uid'];
				}
				if ($member) {
					$rr = pdo_update('xcommunity_member',$data,array('id' => $member['id']));
				}else{
					$r = pdo_insert('xcommunity_member',$data);
				}
				if ($r) {
					$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
				}
				if ($rr) {
					$result = array(
							'status' => 6,
						);
					echo json_encode($result);exit();
				}
		
			
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/register');exit();
		}
	}elseif ($op == 'region') {
		load()->model('mc');
		$userinfo = mc_oauth_userinfo();
		$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE openid=:openid AND status = 0 ",array(':openid' => $_W['fans']['from_user']));
		if ($member) {
			echo "<script language='javascript'>";
			echo "  alert('请耐心等待管理员审核');";
			echo "</script>";exit();
		}
		if ($_W['ispost']) {
			if ($_GPC['keywords']) {
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 5;
				$params = array();
				$params[':weid'] = $_W['uniacid'];
				$condition .="AND title like '%{$_GPC['keywords']}%'";
				$sql = "SELECT id,title,address,linkway,thumb FROM " . tablename('xcommunity_region') . " WHERE weid = :weid $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
            	$list = pdo_fetchall($sql,$params);
            	$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_region')."WHERE weid = :weid $condition",$params);
            	if ($list){
            		foreach ($list as $key => $value) {
            			$list[$key]['thumb'] = tomedia($value['thumb']);
            			
	            	}
            	}
	           $styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
				if ($styleid) {
					include $this->template('style/style'.$styleid.'/location_search');exit();
				}
	       }
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/location');exit();
		}
	}elseif ($op == 'getaround') {
		WeSession::start($_W['uniacid'],$_W['fans']['from_user'],600);
		if($_GPC['longitude']&&$_GPC['latitude']){
			$_SESSION['lng'] = $_GPC['longitude'];
			$_SESSION['lat'] = $_GPC['latitude'];
		}
		$lng = $_SESSION['lng'] ? $_SESSION['lng'] : $_GPC['longitude'];
		$lat = $_SESSION['lat'] ? $_SESSION['lat'] : $_GPC['latitude'];
		if ($_W['isajax']) {

			if ($lng && $lat) {
				$pindex = max(1, intval($_GPC['page']));
				$psize  = 5;
				$params = array();
				$params[':weid'] = $_W['uniacid'];
				$settings = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(":uniacid" => $_W['uniacid']));
				if ($settings['range']) {
					$range = $settings['range'];
				}else{
					$range = 5;
				}
		        $point = $this->squarePoint($lng, $lat, $range);
		        $condition = '';
		        if ($_GPC['keywords']) {
		        	$condition .="AND title like '%{$_GPC['keywords']}%'";
		        }else{
		        	$condition .="AND lat<>0 AND lat >= '{$point['right-bottom']['lat']}' AND lat <= '{$point['left-top']['lat']}' AND lng >= '{$point['left-top']['lng']}' AND lng <= '{$point['right-bottom']['lng']}'";
		        }
		        $sql = "SELECT id,title,address,linkway,thumb,url FROM " . tablename('xcommunity_region') . " WHERE weid = :weid  $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
            	$result = pdo_fetchall($sql,$params);
            	$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_region')."WHERE weid = :weid $condition",$params);
            	$data = array();
            	if ($result){
            		foreach ($result as $key => $value) {
            			$thumb = tomedia($value['thumb']);
            			if ($value['url']) {
            				$url = $value['url'];
            			}else{
            				$url = $this->createMobileUrl('register',array('op' => 'member','regionid' => $value['id']));

            			}
            			$data[]['html'] = "<li><a href='".$url."' class='xq_list_a'><div class='xq_img'><img src='".$thumb."'></div><div class='xq_right'><h3 class='xq_name'>".$value['title']."</h3><p class='xq_address'>地址：".$value['address']."</p><p class='xq_address'>电话：".$value['linkway']."</p></div></a></li>";
	            	}
            	}
	            $r = array(
	            		'allhtml' => $data,
	            		'page_count' => $total,
	            		
	            	);
	           print_r(json_encode($r));exit();
			
			}
		}
	}elseif ($op == 'room') {
		$mobile = intval($_GPC['mobile']);
		$regionid = intval($_GPC['regionid']);
		//是否开启房号注册码验证
		$set = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		if ($set['room_enable']) {
			if ($mobile) {
			//查询小区房号
				$rooms = pdo_fetchall("SELECT code,room FROM".tablename('xcommunity_room')."WHERE uniacid=:uniacid AND mobile=:mobile AND regionid=:regionid",array(':uniacid' => $_W['uniacid'],':mobile' => $mobile,':regionid' => $regionid));
				if ($rooms) {
					$result = array(
								'status' => 1,
								'content' => json_encode($rooms),
							);
						echo json_encode($result);exit();
				}else {
					$region = pdo_fetch("SELECT linkway FROM".tablename('xcommunity_region')."WHERE id=:regionid AND weid=:weid",array(':regionid' => $regionid,':weid' => $_W['uniacid']));

					$result = array(
							'status' => 2,
							'content' => json_encode($region),
						);
					echo json_encode($result);exit();
				
					
				}
			}
		}
		
	} 
	
	
	