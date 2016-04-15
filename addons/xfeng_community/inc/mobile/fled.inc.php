<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 微信端二手交易
 */


	global $_W,$_GPC;

	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$member = $this->changemember();
	$region = $this->mreg();
	//查二手子类 二手主类ID=5
	$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid='{$_W['weid']}' AND type=4");
	if ($op == 'list') {
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 10;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$keyword = "%{$_GPC['keyword']}%";
				$condition .= " AND f.title LIKE '{$keyword}'";
			}
			$category = intval($_GPC['category']);
			if ($category) {
				$condition .=" AND f.category =:category";
				$params[':category'] = $category; 
			}
			$price = intval($_GPC['price']);
			if ($price == 1000) {
				$condition .=" AND f.zprice between 0 and 1000";
			}elseif ($price == 2000) {
				$condition .=" AND f.zprice between 1000 and 2000";
			}elseif ($price == 4000) {
				$condition .=" AND f.zprice between 2000 and 4000";
			}elseif ($price == 6000) {
				$condition .=" AND f.zprice between 4000 and 6000";
			}

			$list = pdo_fetchall('SELECT f.*,s.name as name FROM'.tablename('xcommunity_fled')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' AND black = 0 $condition order by f.id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_fled')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' $condition order by f.id desc",$params);

			foreach ($list as $key => $value) {
				$images = $value['images'];
				if ($images) {
					$imgs   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$images})");
					$list[$key]['img'] = $imgs;
				}
				

				if ($value['regionid']) {
					$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$value['regionid']}'");
					$list[$key]['regionname'] = $region['title'];
				}
			}
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('fled',array('op' => 'detail','id' => $value['id']));
				
				$datetime = date('Y-m-d H:i:s',$value['createtime']);
				
				$data[]['html'] = "<li onclick=\"window.location.href='".$url."'\">
			                            <div class='li_div'>";
			                            if($value['img']){
			                            	$data[]['html'] .="<div class='house_l'><img src='".$value['img'][0]['src']."' /></div>";
			                            }
			                              
			                           $data[]['html'] .="<div class='house_r'>
			                                    <h3>        <span >【".$value['name']."】</span>".$value['title']."</h3>
			                                    <div class='price_div'>
			                                        <div class='price_l'><span class='green'>".$value['rolex']."</span></div>
			                                        <div class='price_r'><span class='price'>";
			                                        if (empty($value['zprice'])) {
			                                        	$data[]['html'] .="面议";
			                                        }else{
			                                        	$data[]['html'] .="{$value['zprice']}";
			                                        }
			                                        $data[]['html'] .="</span></div>
			                                    </div>
			                                </div>
			                            </div>
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
			include $this->template('style/style'.$styleid.'/fled/list');exit();
		}
	}elseif ($op == 'add') {

		$m = mc_fetch($_W['member']['uid'],array('realname','mobile','address'));
		$id = intval($_GPC['id']);
		if (!empty($id)) {
			$good = pdo_fetch("SELECT * FROM".tablename('xcommunity_fled')."WHERE id=:id",array(':id' => $id));
		}
		
		if ($_W['isajax']) {
			$data = array(
				'weid'        => $_W['weid'],
				'openid'      => $_W['fans']['from_user'],
				'rolex'       => $_GPC['rolex'],
				'title'       => $_GPC['title'],
				'category'    => $_GPC['category'],
				'zprice'      => $_GPC['zprice'],
				'description' => $_GPC['description'],
				'realname'    => $_GPC['realname'],
				'mobile'      => $_GPC['mobile'],
				'createtime'  => TIMESTAMP,
				'regionid'    => $member['regionid'],
				'images' => substr($_GPC['picIds'],0,strlen($_GPC['picIds'])-1),
			);
			if (empty($_GPC['id'])) {
				$r = pdo_insert('xcommunity_fled',$data);
			}else{
				$r = pdo_update('xcommunity_fled',$data,array('id' => $_GPC['id']));
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
			include $this->template('style/style'.$styleid.'/fled/add');exit();
		}
	}elseif ($op == 'detail') {
		$id = intval($_GPC['id']);
		if ($id) {
			$item = pdo_fetch("SELECT f.*,s.name as name FROM".tablename('xcommunity_fled')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.id=:id",array(':id' => $id));
			if ($item['images']) {
				$imgs = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$item['images']})");
			}
			
		}
		
		$styleid = pdo_fetchcolumn("SELECT styleid FROM".tablename('xcommunity_template')."WHERE uniacid='{$_W['uniacid']}'");
		if ($styleid) {
			include $this->template('style/style'.$styleid.'/fled/detail');exit();
		}
	}elseif($op == 'my'){
		if ($_W['isajax']) {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 2;
			$condition = '';
			if (!empty($_GPC['keyword'])) {
				$keyword = "%{$_GPC['keyword']}%";
				$condition = " AND f.title LIKE '{$keyword}'";
			}
			$category = intval($_GPC['category']);
			if ($category) {
				$condition .=" AND f.category =:category";
				$params[':category'] = $category; 
			}
			$list = pdo_fetchall('SELECT f.*,s.name as name FROM'.tablename('xcommunity_fled')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' AND f.openid='{$_W['fans']['from_user']}' AND black = 0 $condition order by f.id desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
			$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_fled')."as f left join".tablename('xcommunity_category')."as s on f.category = s.id WHERE f.weid='{$_W['weid']}' AND f.regionid='{$member['regionid']}' AND f.openid='{$_W['fans']['from_user']}' $condition order by f.id desc",$params);

			foreach ($list as $key => $value) {
				if ($value['images']) {
					$images = unserialize($value['images']);
					if ($images) {
						$picid  = implode(',', $images);
						$imgs   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$picid})");
					}
					$list[$key]['img'] = $imgs;
				}
				if ($value['regionid']) {
					$region = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id='{$value['regionid']}'");
					$list[$key]['regionname'] = $region['title'];
				}
			}
			$data = array();
			foreach ($list as $key => $value) {
				$url = $this->createMobileUrl('fled',array('op' => 'detail','id' => $value['id']));
				$datetime = date('Y-m-d H:i:s',$value['createtime']);
				$data[]['html'] = "<a class='weui_cell' href='".$url."'>
						                <div class='weui_cell_bd weui_cell_primary'>
						                    <p>
						                        
						                        <p style='background-color:#48b54e;color:white;width:50px;height:20px; border-radius: 5px;font-size:12px;text-align:center;line-height:20px;float:left'>【".$value['name']."】</p>
						                       

						                       <p style='font-size:12px;line-height:20px;'>&nbsp;&nbsp;".$value['rolex'].$value['title']."</p>
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
			include $this->template('style/style'.$styleid.'/fled/my');exit();
		}
	}elseif ($op == 'delete') {
		$id = intval($_GPC['id']);
		if (pdo_delete('xcommunity_fled',array('id'=>$id))) {
			$result['state'] = 0;
			message($result, '', 'ajax');
		}
	}elseif ($op == 'finish') {
		$id = intval($_GPC['id']);
		if ($id) {
			$r = pdo_update('xcommunity_fled',array('status' => 1),array('id' => $id));
			if ($r) {
				echo json_encode(array('result' => 1));
			}
		}
	}
