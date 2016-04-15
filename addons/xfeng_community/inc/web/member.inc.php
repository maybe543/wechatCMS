<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区用户信息
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	$id        = intval($_GPC['id']);
	if ($op == 'list') { 
			$condition = '';
			if (!empty($_GPC['realname'])) {
				$condition .= " AND m.realname LIKE '%{$_GPC['realname']}%'";
			}
			if (!empty($_GPC['mobile'])) {
				$condition .= " AND m.mobile LIKE '%{$_GPC['mobile']}%'";
			}
			//判断是否是操作员
			$user = $this->user();
			if ($user) {
				if ($user['regionid']) {
					$condition .="AND m.regionid=:regionid";
					$params[':regionid'] = $user['regionid'];
				}else{
					$condition .=" AND r.pid =:pid";
					$params[':pid'] = $user['companyid'];
				}
				
			}
			//显示业主信息
			$pindex = max(1, intval($_GPC['page']));
			$psize  = 10;
			$sql    = "select m.*,r.title as title,r.city,r.dist from ".tablename("xcommunity_member")."as m left join".tablename('xcommunity_region')."as r on m.regionid = r.id where m.weid='{$_W['weid']}' $condition order by m.id desc LIMIT ".($pindex - 1) * $psize.','.$psize;
			$list   = pdo_fetchall($sql,$params);
			foreach ($list as $key => $value) {
				$list[$key]['cctime'] = date('Y-m-d H:i',$value['createtime']);
				$list[$key]['s'] = empty($value['status']) ? '未审核' : '通过';
			}
			$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_member")."as m left join".tablename('xcommunity_region')."as r on m.regionid = r.id where m.weid='{$_W['weid']}' $condition order by m.id desc",$params);
			$pager  = pagination($total, $pindex, $psize);
			if ($_GPC['export'] == 1) {
				$this->export($list,array(
			            "title" => "会员数据-" . date('Y-m-d-H-i', time()),
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
			                    'width' => 12
			                ),
			                array(
			                    'title' => '注册时间',
			                    'field' => 'cctime',
			                    'width' => 12
			                ),
			                array(
			                    'title' => '状态',
			                    'field' => 's',
			                    'width' => 12
			                ),
			            )
			        ));
			}
			include $this->template('web/member/list');

	}elseif($op == 'add') {
		//查看住户信息
		if ($id) {
			$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE id=:id",array(':id' => $id));
		}
		// print_r($member);exit();
		//查看小区信息
		$regions = $this->regions();
		if(checksubmit('submit')){
		//修改用户信息
			$data = array(
				'regionid'   =>$_GPC['_regionid'],
				'remark'     =>$_GPC['remark'],
				'createtime' =>$_W['timestamp'],
				'address'    =>$_GPC['address'],
				'mobile'	 =>$_GPC['mobile']
 			);
			load()->model('mc');
			$r = mc_update($member['memberid'], array('mobile' => $_GPC['mobile'],'realname' => $_GPC['realname'],'address' => $_GPC['address']));
			if ($id) {
				pdo_update("xcommunity_member",$data,array('id' => $id));
				message('修改成功',$this->createWebUrl('member'), 'success');
			}
			
		}	
		include $this->template('web/member/add');	
	}elseif ($op == 'delete') {
		//删除用户
		
			if (empty($id)) {
				exit('缺少参数');
			}
			$r = pdo_delete("xcommunity_member",array('id' => $id));
			if ($r) {
				message('删除成功',referer(),'success');
				
			}
	
	}elseif($op == 'verify'){
		//审核用户
		$id = intval($_GPC['id']);
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);
		if (in_array($type, array('status'))) {
			$data = ($data==1?'0':'1');
			pdo_update("xcommunity_member", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
	}