<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端小区拼车
 */


	global $_W,$_GPC;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$member = $this->changemember();
	$region = $this->mreg();
	$id = intval($_GPC['id']);
	if($op == 'list'){
		$type = !empty($_GPC['type']) ? $_GPC['type'] : 1;
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			
			$list = pdo_fetchall('SELECT * FROM'.tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND black = 0 AND type ='{$type}' order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND type ='{$type}' order by id desc");

			foreach ($list as $key => $value) {
				load()->model('mc');
				 $m = mc_fetch($value['openid']);
				$list[$key]['avatar'] = $m['avatar'];
			}
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('car',array('op' => 'detail','id' => $value['id']));
				
				$datetime = date('Y-m-d H:i',$value['createtime']);
				
				$data[]['html'] = "<li>
		                            <a href=".$url.">
		                                <h5 style='margin : 5px 10px 0px 20px; font-size:14px;color:#F60;'>".$value['title']."</h5>
		                                <div class='box_div'>
		                                    <div class='box_l'>
		                                        <div class='car_header'><img src='".$value['avatar']."' />
		                                            <span class='man'><em></em></span>
		                                        </div>
		                                        <div class='caru_name'>".$value['contact']."</div>
		                                    </div>
		                                    <div class='box_m'>
		                                        <div class='m_content'>
		                                            <div class='sart'>".$value['start_position']."</div>
		                                        </div>
		                                        <div class='m_content'>
		                                            <div class='end'>".$value['end_position']."</div>
		                                        </div>
		                                    </div>
		                                    <div class='box_r'><span class='price'>".$value['sprice']."</span><span class='seat'>".$value['seat']."</span></div>
		                                </div>
		                                <div class='bottom_box'>
		                                    <div class='bottom_l'><span class='sj_img'></span></div>
		                                    <div class='bottom_m'><span style='font-size:12px;'>发布时间:".$datetime."</span></div>
		                                    <div class='bottom_r'><span class='xin'></span></div>
		                                </div>
		                            </a>
		                        </li>";
			}
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}
		
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/car/list');
		}
	}elseif($op == 'add'){
		$m = mc_fetch($_W['member']['uid'],array('realname','mobile','address'));
		if (!empty($id)) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_carpool')."WHERE id=:id",array(':id' => $id));
		}
		
		if ($_W['isajax']) {
			$data = array(
				'weid'           => $_W['weid'],
				'regionid'       => $member['regionid'],
				'openid' => $_W['fans']['from_user'],
				'title' => $_GPC['title'],
				'seat' => $_GPC['seat'],
				'sprice' => $_GPC['sprice'],
				'contact' => $_GPC['contact'],
				'mobile' => $_GPC['mobile'],
				'start_position' => $_GPC['start_position'],
				'end_position' => $_GPC['end_position'],
				'gotime' => $_GPC['gotime'],
				'backtime' => $_GPC['backtime'],
				'type' => intval($_GPC['type']),
				'createtime' => TIMESTAMP,
			);
			if (empty($id)) {
				$r = pdo_insert('xcommunity_carpool',$data);
			}else{
				$r = pdo_update('xcommunity_carpool',$data,array('id' => $id));
			}
			if ($r) {
				$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
			}
		}

		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/car/add');
		}
	}elseif ($op == 'detail') {
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_carpool')."WHERE id=:id",array(':id' => $id));
			load()->model('mc');
			$userinfo = mc_fetch($item['openid'],array('avatar'));
		}

		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/car/detail');
		}
	}elseif ($op == 'my') {
		// $pindex = max(1, intval($_GPC['page']));
		// $psize = 10;
		// $condition = '';
		// $keyword = $_GPC['keyword'];
		// if ($keyword) {
		// 	$keyword = "%{$_GPC['keyword']}%";
		// 	$condition = " AND start_position LIKE '{$keyword}' OR end_position LIKE '{$keyword}'";
		// }
		// $list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND status = 0 AND regionid='{$member['regionid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize);
		
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 2;

			$list = pdo_fetchall('SELECT * FROM'.tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND black = 0 AND openid='{$_W['fans']['from_user']}' order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_carpool')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' order by id desc",$params);
			load()->model('mc');
			$m = mc_fetch($_W['fans']['uid'],array('address'));
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('car',array('op' => 'detail','id' => $value['id']));
				$datetime = date('Y-m-d H:i:s',$value['createtime']);
				$data[]['html'] = "<a class='weui_cell' href='".$url."'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                    <p>
						                        
						                        <p style='color:#48b54e;width:100px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>【我是";
						                        if ($value['type'] == 1) {
						                        	 $data[]['html'].="司机";
						                        }else{
						                        	 $data[]['html'].="乘客";
						                        }

						                        $data[]['html'] .= "】</p>
						                       

						                       <p style='font-size:12px;line-height:20px;'>".$value['title']."</p>
						                    </p>
						                    <p style='font-size:12px;line-height:20px;'>&nbsp;&nbsp;(发布的小区地址：".$m['address'].")</p>
						                   
						                </div>
						            
						                <div class='weui_cell_ft'>
						                </div>
						            </a>
						            <div class='weui_cell'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                	<p style='font-size:12px;color: #a9a9a9;clear:both;margin-top:5px;''>发布时间：".$datetime." </p> 
						                </div>";
						               $data[]['html'].="<div class=\"weui_cell_ft del\"  onclick ='delectFun(".$value['id'].")'>删除</div>&nbsp;&nbsp;";
	
						$data[]['html'] .="</div>
							            <a style='height:20px;width:100%;background-color: #efeef4;display:block'></a>";
			}
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/car/my');exit();
		}

	}elseif ($op == 'delete') {
		if ($id) {
			if (pdo_delete('xcommunity_carpool',array('id' => $_GPC['id']))) {
				$result['state'] = 0;
				message($result, '', 'ajax');
			}
		}
	}















