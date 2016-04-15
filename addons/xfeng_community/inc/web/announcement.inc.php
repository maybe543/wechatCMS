<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区公告信息
 */

	global $_GPC,$_W;
	$GLOBALS['frames'] = $this->NavMenu();
	$op = !empty($_GPC['op'])?$_GPC['op']:'list';
	$id = intval($_GPC['id']);
	//判断是否是操作员
	$user = $this->user();
	if($op == 'list'){
		//公告搜索
		$condition = '';
		if (!empty($_GPC['title'])) {
			$condition .= " AND title LIKE '%{$_GPC['title']}%'";
		}
		if ($user) {
			$condition .=" AND uid='{$_W['uid']}'";
		}

		//管理公告reason
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 10;
		$sql    = "select * from ".tablename("xcommunity_announcement")."where  weid = {$_W['weid']} $condition LIMIT ".($pindex - 1) * $psize.','.$psize;
		$list   = pdo_fetchall($sql);
		$total  = pdo_fetchcolumn('select count(*) from'.tablename("xcommunity_announcement")."where  weid = {$_W['weid']} $condition");
		$pager  = pagination($total, $pindex, $psize);
		include $this->template('web/announcement/list');
	}elseif ($op == 'add') {

		if(!empty($id)){
			$item = pdo_fetch("SELECT * FROM".tablename('xcommunity_announcement')."WHERE id=:id",array(':id' =>$id));
			$regs = iunserializer($item['regionid']);
		}
		if ($user) {
				//物业管理员
				if (!$user['regionid']) {
					$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

				}
			}else{
				$regions = $this->regions();	
			}
		//添加公告
		if(checksubmit('submit')){
			
			$data = array(
					'weid'       => $_W['uniacid'],
					'title'      =>$_GPC['title'],
					'createtime' =>$_W['timestamp'],
					'status'     =>$_GPC['status'],
					'enable'     =>$_GPC['enable'],
					'datetime'   =>$_GPC['datetime'],
					'location'   =>$_GPC['location'],
					'reason'     =>$_GPC['reason'],
					'remark'     =>$_GPC['remark'],
				);
			if ($user) {
					$data['uid'] = $_W['uid'];
				}
				if ($user['regionid']) {
					$data['regionid'] = serialize($user['regionid']);

				}else{
					$data['regionid'] = serialize($_GPC['regionid']);
				}
			if(empty($id)){
				pdo_insert("xcommunity_announcement",$data);
				$id = pdo_insertid();
			}else{
				pdo_update("xcommunity_announcement",$data,array('id'=>$id));
			}
			//是否启用模板消息
			if ($_GPC['status'] == 2) {
				$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
				if ($data['enable'] == 1) {
					$template_id = $tpl['water_tplid'];
				}elseif ($data['enable'] == 2) {
					$template_id = $tpl['gas_tplid'];
				}elseif ($data['enable'] == 3) {
					$template_id = $tpl['power_tplid'];
				}elseif ($data['enable'] == 4) {
					$template_id = $tpl['guard_tplid'];
				}elseif ($data['enable'] == 5) {
					$template_id = $tpl['lift_tplid'];
				}elseif ($data['enable'] == 6) {
					$template_id = $tpl['car_tplid'];
				}elseif ($data['enable'] == 7) {
					$template_id = $tpl['other_tplid'];
				}
				
				if ($user['regionid']) {
					$regionid = intval($user['regionid']);
				}else{
					if (is_array($_GPC['regionid'])) {
						$regionid = implode(',',$_GPC['regionid']);
					}else{
						$regionid = intval($_GPC['regionid']);
					}
				}
				$members = pdo_fetchall("SELECT openid FROM".tablename('xcommunity_member')."WHERE weid=:weid AND regionid in({$regionid})",array(':weid' => $_W['weid']));			
				foreach ($members as $key => $value) {
					$url = $_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&id={$id}&op=detail&do=announcement&m=xfeng_community";
					$openid = $value['openid'];
					if ($data['enable'] == 4) {
						$data = array(

									'first' => array(
											'value' => $_GPC['title'],
										),
									'scope'    => array(
											'value' => $_GPC['location'],
										),
									'time'	=> array(
											'value' => $_GPC['datetime'],
										),
									'method'    => array(
											'value' => $_GPC['reason'],
										),
									'remark'    => array(
											'value' => $_GPC['remark'],
										)
	
						);
						// print_r($data);exit();
					}elseif ($data['enable'] == 7) {
						$data = array(

									'first' => array(
											'value' => $_GPC['title'],
										),
									'keyword1' => array(
											'value' => $_GPC['title'],
										),
									'keyword2'	=> array(
											'value' => $_GPC['datetime'],
										),
									'keyword3'    => array(
											'value' => $_GPC['reason'],
										),
									'remark'    => array(
											'value' => $_GPC['remark'],
										)
	
						);
					}else{
						$data = array(

									'first' => array(
											'value' => $_GPC['title'],
										),
									'time' => array(
											'value' => $_GPC['datetime'],
										),
									'location'	=> array(
											'value' => $_GPC['location'],
										),
									'reason'    => array(
											'value' => $_GPC['reason'],
										),
									'remark'    => array(
											'value' => $_GPC['remark'],
										)
	
						);
					}
					$content = $this->sendtpl($openid,$url,$template_id,$data);

				}
			}
			message('提交成功',referer(), 'success');
		}
		include $this->template('web/announcement/add');
	}elseif ($op == 'delete') {
		//删除公告
		pdo_delete("xcommunity_announcement",array('id'=>$id));
		message('删除成功',referer(), 'success');
	}
	
