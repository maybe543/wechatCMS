<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端房屋租赁
 */


	global $_W,$_GPC;
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id = intval($_GPC['id']);

	//查对应的小区编号
	$member = $this->changemember();
	$region = $this->mreg();
	if($op == 'add'){
		load()->model('mc');
		$m = mc_fetch($_W['member']['uid'],array('realname','mobile','address'));

		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_houselease')."WHERE id=:id",array(':id'=>'$id'));
		}
		$category = !empty($_GPC['category']) ? $_GPC['category'] : 1;

		if($_W['isajax']){
			
			$data = array(
				'weid'     => $_W['weid'],
				'openid'   => $_W['fans']['from_user'],
				'regionid' => $member['regionid'],
				'category' => $category,
				'way'	=> $_GPC['way'],
				'model_room' => $_GPC['model_room'],
				'model_hall' => $_GPC['model_hall'],
				'model_toilet' => $_GPC['model_toilet'],
				'model_area' => $_GPC['model_area'],
				'floor_layer' => $_GPC['floor_layer'],
				'floor_number' => $_GPC['floor_number'],
				'fitment' => $_GPC['fitment'],
				'house' => $_GPC['house'],
				'allocation' => substr($_GPC['allocation'],0,strlen($_GPC['allocation'])-1),
				'price_way' => $_GPC['price_way'],
				'price' => $_GPC['price'],
				'checktime' => $_GPC['checktime'],
				'title' => $_GPC['title'],
				'realname' => $_GPC['realname'],
				'mobile' => $_GPC['mobile'],
				'content' => $_GPC['content'],
				'createtime' =>TIMESTAMP,
				'images' => substr($_GPC['picIds'],0,strlen($_GPC['picIds'])-1),
				
			);
			if (empty($id)) {
				$r = pdo_insert('xcommunity_houselease',$data);
				
			}else{
				$r = pdo_update('xcommunity_houselease',$data,array('id' => $id));
				
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
			include $this->template('style/style'.$styleid.'/houselease/add');exit();
		}
	}elseif ($op == 'list') {
		//房屋租赁列表
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			$category = intval($_GPC['category']);
			if ($category) {
				$condition .=" AND category =:category";
				$params[':category'] = $category; 
			}
			$li = pdo_fetchall('SELECT * FROM'.tablename('xcommunity_houselease')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' $condition order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_houselease')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' $condition order by id desc",$params);
			$list = array();
			foreach ($li as $key => $value) {
				$image = $value['images'];
				
				if ($image) {
					$imgs   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$image})");
					$list[$key]['img'] = $imgs;
				}


				
				$list[$key]['way'] = $value['way'];
				$list[$key]['title'] = $value['title'];
				$list[$key]['price'] = $value['price'];
				$list[$key]['price_way'] = $value['price_way'];
				$list[$key]['model_room'] = $value['model_room'];
				$list[$key]['model_hall'] = $value['model_hall'];
				$list[$key]['model_hall'] = $value['model_toilet'];
				$list[$key]['model_area'] = $value['model_area'];
				$list[$key]['floor_layer'] = $value['floor_layer'];
				$list[$key]['floor_number'] = $value['floor_number'];
				$list[$key]['fitment'] = $value['fitment'];
				$list[$key]['createtime'] = $value['createtime'];
				$list[$key]['id'] = $value['id'];
			}
			// print_r($list);exit();
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('houselease',array('op' => 'detail','id' => $value['id']));
				
				$datetime = date('Y-m-d H:i:s',$value['createtime']);
				
				$data[]['html'] = "<li onClick=\"location.href='".$url."'\">
						    <div class='li_div'>";

						    $data[]['html'].=" <div class='house_l'><img src='".$value['img'][0]['src']."' /></div>";
						        $data[]['html'].="<div class='house_r'>
						            <h3>
								    	<span >[".$value['way']."]</span>".$value['title']."</h3>
												            <div class='price_div'>
												                <div class='price_r'>
												                    <span class='price'>".$value['price']."</span>
												                </div>
												            </div>
												        </div>
												    </div>
												    <div class='other_info'>
												        <span>".$value['price_way']."</span>|<span>";
												        if ($value['way'] == 1) {
												        	$data[]['html'] .="整套";
												        }elseif ($value['way'] == 2) {
												        	$data[]['html'] .="单间";
												        }else{
												        	$data[]['html'] .="床位";
												        }

												       $data[]['html'] .="</span>|<span>".$value['model_room']."室".$value['model_hall']."厅".$value['model_toilet']."卫</span>|<span>".$value['model_area']."m<sup>2</sup></span>|<span>".$value['floor_layer']."/".$value['floor_number']."层</span>|<span>".$value['fitment']."</span></div>
												    <div class='other_time'>发布时间：".$datetime."</div>
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
			include $this->template('style/style'.$styleid.'/houselease/list');exit();
		}
	}elseif ($op == 'my') {
		$category = !empty($_GPC['category']) ? $_GPC['category'] : 1;
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			if ($category) {
				$condition .=" AND category =:category";
				$params[':category'] = $category; 
			}
			$list = pdo_fetchall('SELECT * FROM'.tablename('xcommunity_houselease')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' $condition order by id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_houselease')."WHERE weid='{$_W['weid']}' AND regionid='{$member['regionid']}' AND openid='{$_W['fans']['from_user']}' $condition order by id desc",$params);

			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('houselease',array('op' => 'detail','id' => $value['id']));
				$datetime = date('Y-m-d H:i:s',$value['createtime']);
				$data[]['html'] = "<a class='weui_cell' href='".$url."'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                    <p>
						                        
						                        <p style='color:#48b54e;height:20px;font-size:12px;text-align:center;line-height:20px;float:left'>【".$value['way']."】</p>
						                       

						                       <p style='font-size:12px;line-height:20px;'>&nbsp;&nbsp;".$value['title']."</p>
						                    </p>
						                    
						                   
						                </div>
						            
						                <div class='weui_cell_ft'>
						                </div>
						            </a>
						            <div class='weui_cell'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                	<p style='font-size:12px;color: #a9a9a9;clear:both;margin-top:5px;''>发布时间：".$datetime." </p> 
						                </div>";
						               $data[]['html'].="<div class=\"weui_cell_ft del\"  onclick ='delectFun(".$value['id'].")'>删除</div>&nbsp;&nbsp;";
						             if (empty($value['status'])) {
						             	$data[]['html'].="<div class=\"weui_cell_ft rank\" onclick ='confirmFun(".$value['id'].")'>确认成交</div>";
							            
						             }
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
			include $this->template('style/style'.$styleid.'/houselease/my');exit();
		}
	}elseif($op == 'detail'){
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_houselease')."WHERE weid=:weid AND id=:id",array(':weid' => $_W['uniacid'],':id' => $id));
		//判断是否开启房屋租赁托管服务
		$set = pdo_fetch("SELECT * FROM".tablename('xcommunity_set')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
		if ($set['h_status']) {
			$region = $this->region($item['regionid']);
		}
		if (empty($item)) {
			message('信息已删除或不存在',referer(),'error');
		}
		if ($item['images']) {
				$imgs = pdo_fetchall("SELECT src FROM".tablename('xcommunity_images')."WHERE id in({$item['images']})");
			}
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/houselease/detail');exit();
		}
	}elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		if (pdo_delete('xcommunity_houselease',array('id'=>$id))) {
			$result['state'] = 0;
			message($result, '', 'ajax');
		}
	}elseif ($op == 'finish') {
		$id = intval($_GPC['id']);
		if ($id) {
			$r = pdo_update('xcommunity_houselease',array('status' => 1),array('id' => $id));
			if ($r) {
				echo json_encode(array('result' => 1));
			}
		}
	}






