<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区报修信息
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id = intval($_GPC['id']);
	//查报修子类 报修主类ID=3
	$categories = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid='{$_W['weid']}' AND type=2");
	if($op == 'list'){
		//搜索
		$condtion = ' m.weid =:weid';
		$params[':weid'] = $_W['uniacid'];
		if (!empty($_GPC['category'])) {
			$condtion .= " AND m.category = :category";
			$params[':category'] = $_GPC['category'];
		}
		$status = intval($_GPC['status']);
		if (!empty($status)) {
			$condtion .=" AND m.status = :status";
			$params[':status'] = $status;
		}
		$starttime = strtotime($_GPC['birth']['start']) ;
		$endtime   = strtotime($_GPC['birth']['end']) ;
		if (!empty($starttime) && $starttime==$endtime) {
			$endtime = $endtime+86400-1;
		}
		if ($starttime && $endtime) {
			$condtion .=" AND m.createtime between '{$starttime}' and '{$endtime}'";
		}
		//print_r($condition);exit();
		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		//判断是否是操作员
		// $user = $this->user();
		// if ($user) {
		// 	$condition .="AND a.regionid=:regionid";
		// 	$params[':regionid'] = $user['regionid'];
		// }
		// if (!$user) {
		// 		$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}'");
		// 		$regionid = intval($_GPC['regionid']);
		// 		if ($regionid) {
		// 			$condition .=" AND a.regionid =:regionid";
		// 			$params[':regionid'] = $regionid;
		// 		}
		// }
		//判断是否是操作员
			$user = $this->user();
			if ($user) {
				if ($user['regionid']) {
					$condtion .="AND m.regionid=:regionid";
					$params[':regionid'] = $user['regionid'];
				}else{
					$condition .=" AND r.pid =:pid";
					$params[':pid'] = $user['companyid'];
				}
				
			}	
		//显示报修记录
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		// $sql    = "select a.regionid,a.comment,a.id,a.category,a.content,a.createtime,a.status,b.realname as realname ,b.mobile as mobile,b.address as address from".tablename("xcommunity_report")."as a left join".tablename("xcommunity_member")."as b on a.openid=b.openid where a.weid='{$_W['weid']}' $condition  and a.type = 1 LIMIT ".($pindex - 1) * $psize.','.$psize;
		// $list   = pdo_fetchall($sql,$params);
		// foreach ($list as $key => $value) {
		// 	load()->model('mc');
		// 	$member = mc_fetch($value['memberid'],array('realname','mobile','address'));
		// 	$member = $this->member($value['openid']);
		// 	$list[$key]['realname'] = $member['realname'];
		// 	$list[$key]['mobile'] = $member['mobile'];
		// }
		// print_r($list);exit();
		$list = pdo_fetchall("SELECT m.content,m.comment,m.category,m.createtime,m.status,b.realname,b.mobile,m.id,r.title,r.city,r.dist,r.address FROM".tablename('xcommunity_report')."as m left join(".tablename('xcommunity_region')."as r left join".tablename('xcommunity_member')."as b on b.regionid = r.id ) on m.openid = b.openid WHERE $condtion AND m.type = 1 order by createtime desc LIMIT ".($pindex - 1) * $psize.','.$psize,$params);
		foreach ($list as $key => $value) {
				$list[$key]['cctime'] = date('Y-m-d H:i',$value['createtime']);
			}
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_report')."as m left join(".tablename('xcommunity_region')."as r left join".tablename('xcommunity_member')."as b on b.regionid = r.id ) on m.openid = b.openid WHERE $condtion AND m.type = 1",$params);
		//print_r($_GPC['export']);exit();
		if ($_GPC['export'] == 1) {
				$this->export($list,array(
			            "title" => "意见报修数据-" . date('Y-m-d-H-i', time()),
			            "columns" => array(
			                array(
			                    'title' => '姓名',
			                    'field' => 'realname',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '手机号',
			                    'field' => 'mobile',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '地址',
			                    'field' => 'address',
			                    'width' => 18
			                ),
			                array(
			                    'title' => '报修内容',
			                    'field' => 'content',
			                    'width' => 20
			                ),
			                array(
			                    'title' => '评价',
			                    'field' => 'comment',
			                    'width' => 20
			                ),
			                array(
			                    'title' => '时间',
			                    'field' => 'cctime',
			                    'width' => 12
			                ),
			            )
			        ));
		}
		//$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_report")."as a left join".tablename("xcommunity_member")."as b on a.openid=b.openid where a.weid='{$_W['weid']}' $condition and a.type = 1");
		
		$pager  = pagination($total, $pindex, $psize);
		load()->func('tpl');
		include $this->template('web/repair/list');
		
	}elseif ($op == 'add') {
		//报修详情
		$sql    = "select a.*,b.memberid from".tablename("xcommunity_report")."as a left join".tablename("xcommunity_member")."as b on a.openid=b.openid where a.weid='{$_W['weid']}' and a.id='{$id}'";
		$item  = pdo_fetch($sql);
		//load()->model('mc');
		//$member = mc_fetch($value['memberid'],array('realname','mobile','address'));
		$member = $this->member($item['openid']);
		$images = $item['images'];
		if ($images) {
		    $imgs   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_images')."WHERE id in({$images})");
		}


		if (checksubmit('submit')) {
			//获取报修表单提交的数据
			$data =array(
				'status'      => $_GPC['status'],
				'resolver'    => $_GPC['resolver'] ? $_GPC['resolver'] : $_W['username'],
				'resolvetime' => $_W['timestamp'],
				'resolve'     => $_GPC['resolve'],
				);
			pdo_update("xcommunity_report",$data,array('id'=>$id));
			if ($data['status'] == 1) {
				//模板消息通知
				$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
				$template_id = $tpl['grab_wc_tplid'];
				$content = array(
							'first' => array(
									'value' => '您的报修已处理',
								),
							'keyword1' => array(
									'value' => $item['content'],
								),
							'keyword2' => array(
									'value' => $member['realname'],
								),
							'keyword3'	=> array(
									'value' => date('Y-m-d H:i',TIMESTAMP),
								),
							'remark'    => array(
								'value' => '请到微信我的报修给我们评价，谢谢使用！',
							),	
						);
					$result = $this->sendtpl($item['openid'],$url,$template_id,$content);
			}
			
			message('更新成功!',referer(),'success');
		}
		include $this->template('web/repair/add');
	}elseif ($op == 'delete') {
		pdo_delete("xcommunity_report",array('id'=>$id));
		message('删除成功！',referer(),'success');
	}elseif ($op == 'category') {
		$list = pdo_fetchall("SELECT * FROM".tablename('xcommunity_category')."WHERE weid=:weid AND type =2",array(':weid' => $_W['weid']));
		if (checksubmit('submit')) {
			$count = count($_GPC['names']);
			// print_r($count);exit();
			for ($i=0; $i < $count; $i++) { 
				$ids = $_GPC['ids'];
				$id  = trim(implode(',', $ids),',');
				$data = array(
									'name'   =>  $_GPC['names'][$i] ,
									'weid'    =>  $_W['weid'],
									'type' => 2,
				 			);
				if($ids[$i]){
					$r = pdo_update("xcommunity_category",$data,array('id'=>$ids[$i]));
				}else{
					$r = pdo_insert("xcommunity_category",$data);
				}
			}
			message('提交成功',$this->createWebUrl('repair',array('op' => 'list')));

		}

		include $this->template('web/repair/category');
	}elseif ($op == 'del') {
		if ($id) {
			pdo_delete("xcommunity_category",array('id' => $id));
			message('删除成功。',referer(),'success');
		}
		
	}
