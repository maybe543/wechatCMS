<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区信息
 */
	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$uid = $_W['uid'];
	$id = intval($_GPC['id']);	
	if ($op == 'add') {
		// $name = "xiaofeng_numbercontrol";
		// $module = pdo_fetch("SELECT * FROM".tablename('modules')."WHERE name='{$name}'");
		// if ($module) {
		// 	$user = pdo_fetch("SELECT * FROM".tablename('xiaofeng_users')."as u left join".tablename('xiaofeng_users_group')."as g on u.groupid = g.id WHERE u.uid=:uid",array(':uid' => $uid));
		// 	if ($user) {
		// 		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		// 		if($total == $user['maxaccount']){
		// 			message("已经达到添加小区上限",$this->createWebUrl('region',array('op' => 'display')),'success');exit();
		// 		}
		// 	}
		// }
		if ($id) {
				$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE weid=:weid AND id=:id",array(":id" => $id,":weid" => $_W['weid']));
				if (empty($item)) {
					message('不存在该小区信息或已删除',referer(),'error');
				}
			}
		if (checksubmit('submit')) {
			$reside = $_GPC['reside'];
			$data = array(
					'weid' => $_W['weid'],
					'title' => $_GPC['title'],
					'linkmen' => $_GPC['linkmen'],
					'linkway' => $_GPC['linkway'],
					'lng' => $_GPC['baidumap']['lng'],
                	'lat' => $_GPC['baidumap']['lat'],
                	'address' => $_GPC['address'],
                	'url' => $_GPC['url'],
                	'thumb' => $_GPC['thumb'],
                	'qq' => $_GPC['qq'],
                	'province' => $reside['province'],
					'city' => $reside['city'],
					'dist' => $reside['district'],
				);
			if ($id) {
				pdo_update("xcommunity_region",$data,array('id'=>$_GPC['id']));
			}else{
				$region = pdo_fetch("SELECT id FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND title='{$_GPC['title']}' AND province='{$_GPC['province']}' AND city='{$_GPC['city']}' AND dist='{$_GPC['dist']}'");
				if ($region) {
					message('该小区已经存在,无需在添加.',referer(),'error');
				}
				pdo_insert("xcommunity_region",$data);
			}
			message('提交成功',referer(), 'success');
		}
		load()->func('tpl');
		include $this->template('web/region/add');
	}elseif ($op == 'list') {
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		if (!empty($_GPC['keyword'])) {
			$condition .= " AND r.title LIKE :keyword";
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		$reside = $_GPC['reside'];
		// print_r($reside);
		if (!empty($reside)) {
			if ($reside['province']) {
				$condition .= " AND r.province = :province";
				$params[':province'] = $reside['province'];
			}
			if ($reside['city']) {
				$condition .= " AND r.city = :city";
				$params[':city'] = $reside['city'];
			}
			if ($reside['dist']) {
				$condition .= " AND r.dist = :dist";
				$params[':dist'] = $reside['dist'];
			}
			
		}
		//判断是否是操作员
		$user = $this->user();
		if ($user) {
			if ($user['regionid']) {
				$condition .="AND r.id=:id";
				$params[':id'] = $user['regionid'];
			}else{
				$condition .="AND r.pid=:pid";
				$params[':pid'] = $user['companyid'];
			}
			
		}
		$pid = intval($_GPC['pid']);
		if ($pid) {
			$condition .=" AND r.pid = :pid";
			$params[':pid'] = $pid;
		}
		$list = pdo_fetchall("SELECT r.address as address,r.province as province,r.city as city ,r.dist as dist,r.qq,r.title as rtitle ,r.id,r.linkmen,r.linkway,p.title as ptitle,r.url FROM".tablename('xcommunity_region')."as r left join ".tablename('xcommunity_property')."as p on r.pid = p.id WHERE r.weid='{$_W['weid']}' $condition LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_region')."as r left join ".tablename('xcommunity_property')."as p on r.pid = p.id WHERE r.weid='{$_W['weid']}' $condition",$params);
		$pager  = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('web/region/list');
	}elseif ($op == 'delete') {
		//小区删除
		if ($id) {
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_region')."WHERE id=:id AND weid=:weid",array(":id" => $id,":weid" => $_W['weid']));
			if (empty($item)) {
				message("不存在该小区信息或已删除",referer(),'error');
			}
			pdo_delete('xcommunity_region',array('id' => $_GPC['id']));
			pdo_delete('xcommunity_member',array('regionid' => $_GPC['id']));
			message('删除成功',referer(), 'success');
		}

	}elseif ($op == 'room') {
		//房号导入
		if (checksubmit('submit')) {
				if (!empty($_FILES['room']['name'])) {
						$tmp_file   = $_FILES['room']['tmp_name'];
						$file_types = explode(".",$_FILES['room']['name']);
						$file_type  = $file_types[count($file_types)-1];
						/*判别是不是.xls文件，判别是不是excel文件*/
						if (strtolower ( $file_type ) !="xls" && strtolower ( $file_type ) !="xlsx") 
						{
							message('类型不正确，请重新上传',referer(),'error');
						}
					  /*设置上传路径*/
					   $savePath = IA_ROOT.'/addons/xfeng_community/template/upFile/';
					  /*以时间来命名上传的文件*/
					   $str = date('Ymdhis'); 
					   $file_name = $str.".".$file_type;
					   /*是否上传成功*/
					   if (!copy($tmp_file,$savePath.$file_name)) {
					   		message('上传失败');
					     
					   }
					  $res = $this->read($savePath.$file_name);
					  $result = pdo_fetch("SELECT * FROM".tablename('xcommunity_room')."WHERE regionid=:id AND uniacid=:uniacid ",array(':uniacid' => $_W['uniacid'],':id' => $id));
				  	  if ($result) {
				  	  	message('该小区已存在房号数据',referer(),'success');exit();
				  	  }
					  /*对生成的数组进行数据库的写入*/
					  foreach ( $res as $k => $v ) {
						    if ($k != 0) {
								$data['room']     = $v[0];
								$data['mobile']   = $v[1];
								$data['code']     = random(8);
								$data['regionid'] = $id;
								$data['uniacid']  = $_W['uniacid'];
								//print_r($data);exit();
								$result = pdo_insert('xcommunity_room',$data);
						    }
					  }

					  if($result){
				       		message('导入成功',referer(),'success');
				     	}
					}
				}

		include $this->template('web/region/room');
	}elseif ($op == 'rlist') {
		//房号显示
		// $regionid = intval($_GPC['id']);
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = 'uniacid=:uniacid';
		$params[':uniacid'] = $_W['uniacid'];
		$condition .= ' AND regionid=:regionid';
		$params[':regionid'] = $id;
		if (!empty($_GPC['room'])) {
			$condition .= " AND room LIKE :keyword";
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		$sql = "SELECT * FROM".tablename('xcommunity_room')."WHERE $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list = pdo_fetchall($sql,$params);
		$tsql = "SELECT COUNT(*) FROM".tablename('xcommunity_room')."WHERE $condition";
		$total =pdo_fetchcolumn($tsql,$params);
		$pager  = pagination($total, $pindex, $psize);
		//删除用户
		if (checksubmit('delete')) {
			$ids=$_GPC['rid'];
			if (!empty($ids)) {
				foreach ($ids as $key => $id) {
					pdo_delete('xcommunity_room',array('id' => $id));
				}
				message('删除成功',referer(),'success');
			}
		}
		if (checksubmit('submit')) {
			$data = array(
					'uniacid' => $_W['uniacid'],
					'room' => $_GPC['room'],
					'mobile' => $_GPC['mobile'],
					'regionid' => $id,
					'code' => random(8)
				);
			$room = pdo_fetch("SELECT * FROM".tablename('xcommunity_room')."WHERE mobile=:mobile or room=:room",array(':mobile' => $data['mobile'],':room' => $data['room']));
			if ($room) {
				message('手机号码或者房号已存在',$this->createWebUrl('region',array('op' => 'rlist','id' => $id)),'error');exit();
			}
			if (empty($room)) {
				$r = pdo_insert('xcommunity_room',$data);
				if ($r) {
					message('添加成功',$this->createWebUrl('region',array('op' => 'rlist','id' => $id)),'success');
				}
			}
			
		}
		include $this->template('web/region/rlist');
	}elseif ($op == 'edit') {
		//房号编辑
		$rid = intval($_GPC['rid']);
		if (empty($rid)) {
			message('缺少参数',referer,'error');
		}
		if ($rid) {
			$item = pdo_fetch("SELECT room,mobile FROM".tablename('xcommunity_room')."WHERE id=:id",array(':id' => $rid));
		}
		if (checksubmit('submit')) {
			$data = array(
					'room' => $_GPC['room'],
					'mobile' => $_GPC['mobile'],
				);
			if ($rid) {
				$r = pdo_update('xcommunity_room',$data,array('id' => $rid));
				if ($r) {
					message('修改成功',$this->createWebUrl('region',array('op' => 'rlist','id' => $id)),'success');
				}
			}
		}
		include $this->template('web/region/edit');
	}












	