<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端报修
 */


	global $_GPC,$_W;
	$op = !empty($_GPC['op'])?$_GPC['op']:'list';
	//查小区编号
	$member = $this->changemember();
	$region = $this->mreg();
	load()->model('mc');
	$m = mc_fetch($_W['fans']['uid'],array('mobile','address','realname'));
	if($op == 'add'){
		//查报修子类 报修主类ID=3
		$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid='{$_W['weid']}' AND type=2");
		$m = mc_fetch($_W['member']['uid'],array('realname','mobile','address'));
		if ($_W['isajax']) {
			$data  = array(
				'openid'      => $_W['fans']['from_user'],
				'weid'        => $_W['weid'],
				'regionid'    => $member['regionid'],
				'type'        => 1,
				'category'    => $_GPC['category'],
				'content'     => $_GPC['content'],
				'createtime'  => $_W['timestamp'],
				'images' => substr($_GPC['picIds'],0,strlen($_GPC['picIds'])-1),
				'status' => 2,
				'address' => $_GPC['address'],
			);
			

			$r = pdo_insert("xcommunity_report",$data);
			$id = pdo_insertid();
			
			
			//微信通知
			$notice = pdo_fetchall("SELECT * FROM".tablename('xcommunity_wechat_notice')."WHERE uniacid=:uniacid",array('uniacid' => $_W['uniacid']));
			// $list = array();
			foreach ($notice as $key => $value) {
				if ($value['type'] == 1 || $value['type'] == 3) {
					$regions = unserialize($value['regionid']);
					if (@in_array($member['regionid'], $regions)) {
						// $list = $notice;
						if ($value['repair_status'] == 2) {
							//短信提醒
							$mmember = $this->member($value['fansopenid']); //管理员手机
							$content = $_GPC['content'];
							$sms = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_smsid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
							if ($sms['report_type']) {
								$tpl_id = $sms['reportid'];
								$appkey = $sms['sms_account'];
								// $mobile = $m['mobile'];
								$this->Resms($content,$tpl_id,$appkey,$mmember['mobile'],$member['mobile']);
							}
							//模板消息通知
							$openid = $value['fansopenid'];
							$url = $_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&op=grab&id={$id}&do=repair&m=xfeng_community";
							$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
							$template_id = $tpl['repair_tplid'];
							$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
							$content = array(
									'first' => array(
											'value' => '新报修通知',
										),
									'keyword1' => array(
											'value' => $member['realname'],
										),
									'keyword2' => array(
											'value' => $member['mobile'],
										),
									'keyword3'	=> array(
											'value' => $member['address'],
										),
									'keyword4'    => array(
											'value' => $_GPC['content'],
										),
									'keyword5'    => array(
											'value' => $createtime,
										),
									'remark'    => array(
										'value' => '请尽快联系客户。',
									),	
								);
							$this->sendtpl($openid,$url,$template_id,$content);
						}
					}
				}
				
			}
			// foreach ($list as $key => $value) {
				
			// }
			//判断打印机
			$prints = pdo_fetchall("SELECT * FROM".tablename('xcommunity_print')."WHERE uniacid = :uniacid",array(':uniacid' => $_W['uniacid']));
			$row = array();
			foreach ($prints as $key => $value) {
				$regions = unserialize($value['regionid']);
				if (@in_array($member['regionid'], $regions)) {
					$row = $prints;
				}
			}
			foreach ($row as $key => $value) {

				if ($value['print_status']) {

					if (empty($value['print_type']) || $value['print_type'] == '1') {
						$key = $value['api_key'];
						$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
						$msgNo = time()+1;
						$deviceNo = $value['deviceNo'];
						if ($value['member_code']) {
								$freeMessage = array(
										'memberCode'=>$value['member_code'], 
										'msgDetail'=>
												'
												    物业公司欢迎您报修

												内容：'.$_GPC['content'].'
												-------------------------

												地址：'.$member['address'].'
												业主：'.$member['realname'].'
												电话：'.$member['mobile'].'
												时间：'.$createtime.'
												',
										'deviceNo'=>$deviceNo, 
										'msgNo'=>$msgNo,
									);
								echo $this->sendFreeMessage($freeMessage,$key);
						}else{
							//普通打印机
						
								$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
								$content = "^N1^F1\n";
								$content .= "^B2 新报修订单\n";
								$content .="内容：".$_GPC['content']."\n";
								$content .="地址：".$member['address']."\n";
								$content .="业主：".$member['realname']."\n";
								$content .="电话：".$member['mobile']."\n";
								$content .="时间：".$createtime;

								$c= $this->sendSelfFormatOrderInfo($deviceNo, $key, 1,$content);
							
						}

					}
				}
				
			}
			unset($result);
			if ($r) {
				$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
			}
			
		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/add');exit();
		}
	}elseif ($op == 'list') {

		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = " ";
			$status = intval($_GPC['status']);
			if ($status) {
				$condition .=" AND status=:status";
				$parmas[':status'] = $status; 
			}
			$sql = "select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=1  AND regionid='{$member['regionid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
			// print_r($sql);exit();
			$list    = pdo_fetchall($sql,$parmas);

			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_report')."WHERE weid='{$_W['weid']}' and type=1  AND regionid='{$member['regionid']}' $condition ",$parmas);
			
			$data = array();
	    	if ($list){
	    		foreach ($list as $key => $value) {
	   
	    			$url = $this->createMobileUrl('repair',array('op' => 'detail','id' => $value['id']));
	    			$datetime = date('Y-m-d H:i',$value['createtime']);
	    			$data[]['html'] = "<a class='weui_cell' href='".$url."'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                    <p>";
						                        if ($value['status'] == 1) {
						                        	$data[]['html'].="<p style='background-color:#48b54e;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>已处理</p>";
						                        }elseif ($value['status'] == 2) {
						                        	$data[]['html'].=" <p style='background-color:#eb223f;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>未处理</p>";
						                        }elseif ($value['status'] == 3) {
						                        	$data[]['html'].=" <p style='background-color:#09bb07;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>处理中</p>";
						                        }

						                       $data[]['html'].=" <p style='font-size:12px;line-height:20px;''>&nbsp;&nbsp;".$value['category']."</p>
						                    </p>
						                    <p style='font-size:12px;color: #a9a9a9;clear:both;margin-top:5px;''>报修日期：".$datetime."</p>
						                </div>
						                <div class='weui_cell_ft'>
						                </div>
						            </a>";
	        	}
	    	}
			
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();
		}

		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/list');exit();
		}
	}elseif ($op == 'rank') {
		//业主评论
		if($_W['isajax']){
			$id   = intval($_GPC['id']);
			$data = array(
				'rank'    => $_GPC['rank'],
				'comment' => $_GPC['comment'],
			);
			$r = pdo_update("xcommunity_report",$data,array('id' => $id));
			if ($r) {
				$result = array(
							'status' => 1,
						);
					echo json_encode($result);exit();
			}
	 	}
	 	$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/rank');exit();
		}
	}elseif ($op == 'my') {
		if($_W['isajax']){
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = " ";
			$status = intval($_GPC['status']);
			if ($status) {
				$condition .=" AND status=:status";
				$parmas[':status'] = $status; 
			}
			$list    = pdo_fetchall("select * from ".tablename("xcommunity_report")."where weid='{$_W['weid']}' and type=1  AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$parmas);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_report')."WHERE weid='{$_W['weid']}' and type=1  AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' $condition ",$parmas);
			
			$data = array();
	    	if ($list){
	    		foreach ($list as $key => $value) {
	   
	    			$url = $this->createMobileUrl('repair',array('op' => 'detail','id' => $value['id']));
	    			$u = $this->createMobileUrl('repair',array('op' => 'rank','id' => $value['id']));
	    			$datetime = date('Y-m-d H:i',$value['createtime']);
	    			$data[]['html'] = "<a class='weui_cell' href='".$url."'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                    <p>";
						                        if ($value['status'] == 1) {
						                        	$data[]['html'].="<p style='background-color:#48b54e;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>已处理</p>";
						                        }elseif ($value['status'] == 2) {
						                        	$data[]['html'].=" <p style='background-color:#eb223f;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>未处理</p>";
						                        }elseif ($value['status'] == 3) {
						                        	$data[]['html'].=" <p style='background-color:#09bb07;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>处理中</p>";
						                        }

						                       $data[]['html'].=" <p style='font-size:12px;line-height:20px;''>&nbsp;&nbsp;".$value['category']."</p>
						                    </p>
						                    
						                   
						                </div>
						            
						                <div class='weui_cell_ft'>
						                </div>
						            </a>
						            <div class='weui_cell'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                	<p style='font-size:12px;color: #a9a9a9;clear:both;margin-top:5px;''>报修日期：".$datetime." </p> 
						                </div>
						                <div class=\"weui_cell_bd del\" onclick=\"del(".$value['id'].")\" >删除</div>
						                ";
						             if (empty($value['rank']) && $value['status'] == 1) {
						             	$data[]['html'].="<div class=\"weui_cell_bd rank\" onclick=\"window.location.href='".$u."'\" >待评价</div>";
							            
						             }
						$data[]['html'] .="</div>
							            <a style='height:20px;width:100%;background-color: #efeef4;display:block'></a>";
						                
	        	}
	    	}
			
			$r = array(
		    		'allhtml' => $data,
		    		'page_count' => $total,
		    		
		    	);

		   print_r(json_encode($r));exit();


		}
		
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/my');exit();
		}



	}elseif ($op == 'grab') {
		$id = intval($_GPC['id']);
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$item =pdo_fetch("SELECT * FROM".tablename('xcommunity_report')."WHERE id=:id",array(':id' => $id));
		if ($item['images']) {
			$imgs = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$item['images']})");
		}

		if (empty($item['resolver'])) {
			pdo_update('xcommunity_report',array('resolver' => $_W['fans']['from_user'],'status' => 1,'resolvetime' => TIMESTAMP),array('id' => $id));
		}
		if (checksubmit('submit')) {
			$status = intval($_GPC['status']);
			pdo_update('xcommunity_report',array('status' => $status,'resolvetime' => TIMESTAMP),array('id' => $id));
			if ($status == 2) {
				$openid = $item['openid'];
				$member = pdo_fetch("SELECT realname FROM".tablename('xcommunity_member')."WHERE openid=:openid",array(':openid' => $openid));
				$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
				$template_id = $tpl['grab_wc_tplid'];
				$url = '';
				$content = array(
						'first' => array(
								'value' => '您的服务已处理',
							),
						'keyword1' => array(
								'value' => $item['content'],
							),
						'keyword2' => array(
								'value' => $member['realname'],
							),
						'keyword3'	=> array(
								'value' => date('Y-m-d H:i',timestamp),
							),
						'remark'    => array(
							'value' => '谢谢使用。',
						),	
					);
				$result = $this->sendtpl($openid,$url,$template_id,$content);
			}
			message('处理完成',referer(),'success');

		}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/grab');exit();
		}
	}elseif ($op == 'detail') {
		$id = intval($_GPC['id']);
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$item =pdo_fetch("SELECT * FROM".tablename('xcommunity_report')."WHERE id=:id",array(':id' => $id));
		if ($item['images']) {
			$imgs = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$item['images']})");
		}


		$member = $this->member($item['openid']);
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/repair/detail');exit();
		}
	}elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		if ($_W['isajax']) {
			if (empty($id)) {
				exit('缺少参数');
			}
			$r = pdo_delete('xcommunity_report',array('id' => $id));
			if ($r) {
				$result = array(
						'status' => 1,
					);
				echo json_encode($result);exit();
			}
		}
	}	
	

