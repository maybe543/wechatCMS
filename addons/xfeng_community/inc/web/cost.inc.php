<?php
/**
 * 微小区模块
 *
 * [微赞] Copyright (c) 2013 012wz.com
 */
/**
 * 后台小区物业费
 */
	
global $_GPC,$_W;
$GLOBALS['frames'] = $this->NavMenu();
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
$id = intval($_GPC['id']);
$user = $this->user();
if ($op == 'list') {
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 10;
	$condition = '';
	//判断是否是操作员
	// $user = $this->user();
	// if ($user) {
	// 	$condition .="AND c.regionid=:regionid";
	// 	$params[':regionid'] = $user['regionid'];
	// }
	
	if ($user) {
		if ($user['regionid']) {
			$condition .="AND c.regionid=:regionid";
			$params[':regionid'] = $user['regionid'];
		}else{
			$condition .=" AND r.pid =:pid";
			$params[':pid'] = $user['companyid'];
		}
		
	}
	$list = pdo_fetchall("SELECT c.* , r.title as title,r.city,r.dist  FROM".tablename('xcommunity_cost')."as c left join".tablename('xcommunity_region')."as r on c.regionid = r.id WHERE c.weid='{$_W['weid']}' $condition",$params);
	$total  = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_cost')."as c left join".tablename('xcommunity_region')."as r on c.regionid = r.id WHERE c.weid='{$_W['weid']}' $condition",$params);
	$pager  = pagination($total, $pindex, $psize);

	include $this->template('web/cost/list');
}elseif ($op == 'add') {
	//判断是否是操作员
	if ($user) {
		//物业管理员
		if (!$user['regionid']) {
			$regions = pdo_fetchall("SELECT * FROM".tablename('xcommunity_region')."WHERE weid='{$_W['weid']}' AND pid=:pid",array(':pid' => $user['companyid']));

		}
	}else{
		$regions = $this->regions();	
	}
	if (checksubmit('submit')) {
		
		$costtime = $_GPC['costtime'];
	if (!empty($_FILES['uploadExcel']['name'])) {
			$tmp_file   = $_FILES['uploadExcel']['tmp_name'];
			$file_types = explode(".",$_FILES['uploadExcel']['name']);
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
		   if ($user['regionid']) {
					$regionid = $user['regionid'];
				}else{
					$regionid = $_GPC['regionid'];
				}
		  $insert['costtime']    = $costtime;
		  $insert['regionid'] = $regionid;
		  $res = $this->read($savePath.$file_name);
		  $result = pdo_fetch("SELECT * FROM".tablename('xcommunity_cost')."WHERE costtime='{$insert['costtime']}' AND weid='{$_W['weid']}' AND regionid=:regionid",array(':regionid' => $insert['regionid']));
	  	  if ($result) {
	  	  	message('该时间中已存在数据',referer(),'success');exit();
	  	  }
		  $insert['weid']		= $_W['weid'];
		  $insert['createtime'] = TIMESTAMP;
		  //print_r($res);exit();
		  pdo_insert('xcommunity_cost',$insert);
		  $cid = pdo_insertid();
		  /*对生成的数组进行数据库的写入*/
		  foreach ( $res as $k => $v ) {
			    if ($k != 0) {
					$data['mobile']      = $v[1];
					$data['username']    = $v[0];
					$data['homenumber']  = $v[2];
					$data['costtime']    = $v[3];
					$data['propertyfee'] = $v[4];
					$data['otherfee']    = $v[5];
					$data['total']       = $v[6];
					$data['weid']        = $_W['weid'];
					$data['cid']         = $cid;
					$data['regionid']    = $regionid;
					$data['status']      = $v[7];
					$data['createtime']  = TIMESTAMP;
					$result              = pdo_insert('xcommunity_cost_list',$data);
			    }
		  }

		  if($result){
	       		message('导入成功',referer(),'success');
	     	}
		}
	}

include $this->template('web/cost/add');
}elseif ($op == 'delete') {
	if (empty($id)) {
		message('缺少参数',referer(),'error');
	}
	$result    = pdo_delete('xcommunity_cost',array('id' => $id,'weid' => $_W['weid']));	
	$res       = pdo_delete('xcommunity_cost_list',array('cid' => $id));

	message('删除成功',referer(),'success');

}elseif($op == 'detail'){
	if (empty($id)) {
		message('缺少参数',referer(),'error');
	}
	$condition = '';
	if($_GPC['mobile']){
		$condition .= "AND mobile='{$_GPC['mobile']}'";
	}
	if($_GPC['username']){
		$condition .= " AND username='{$_GPC['username']}'";
	}
	if (intval($_GPC['status'])) {
		$condition .= " AND status='{$_GPC['status']}'";
	}
	// print_r($condition);exit();
	$pindex = max(1, intval($_GPC['page']));
	$psize  = 10;
	$list   = pdo_fetchall("SELECT * FROM".tablename('xcommunity_cost_list')."where weid=:weid AND cid =:id $condition LIMIT ".($pindex - 1) * $psize.','.$psize,array(':weid' => $_W['uniacid'],':id' => $id));
	$total  = pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_cost_list')."WHERE weid=:weid AND cid =:id $condition",array(':weid' => $_W['uniacid'],':id' => $id));
	$pager  = pagination($total, $pindex, $psize);
	$regionid = intval($_GPC['regionid']);
	if ($regionid) {
		$region = $this->region($regionid);
	}
	//物业费短信通知提醒
	if(checksubmit('sms')){
		$cids=$_GPC['cid'];
		// print_r($cids);exit();
		if(!empty($cids)){
    		foreach ($cids as $cid) {
    			if(!empty($cid)){
    				$cost = pdo_fetch("SELECT * FROM".tablename('xcommunity_cost_list')."WHERE id='{$cid}'");
    				$sms = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_smsid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
					load()->func('communication');
					$tpl_id    = $sms['property_id'];
					$price 		= $cost['total'];
					$phone = $region['linkway'];
					$tpl_value = urlencode("#price#=$price&#phone#=$phone");
					$appkey    = $sms['sms_account'];
					$params    = "mobile=".$cost['mobile']."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value."&key=".$appkey;
					$url       = 'http://v.juhe.cn/sms/send';
					$content   = ihttp_post($url,$params);
						
				}

    		}   
			message('发送成功！',referer(), 'success');    
		}else{
			message('请选择要发送的用户！');
		}		    			
	}
	//物业费微信通知提醒
	if (checksubmit('wechat')) {
		$cids=$_GPC['cid'];
		if (!empty($cids)) {
			foreach ($cids as $cid) {
				$cost = pdo_fetch("SELECT * FROM".tablename('xcommunity_cost_list')."WHERE id='{$cid}'");
				$member = pdo_fetch("SELECT * FROM".tablename('xcommunity_member')."WHERE mobile='{$cost['mobile']}'");
				$openid = $member['openid'];
				$url = $_W['siteroot']."app/index.php?i={$_W['uniacid']}&c=entry&op=detail&do=cost&id={$cid}&m=xfeng_community";
				$tpl = pdo_fetch("SELECT * FROM".tablename('xcommunity_wechat_tplid')."WHERE uniacid=:uniacid",array(':uniacid' => $_W['uniacid']));
				$template_id = $tpl['property_tplid'];
				$createtime = date('Y-m-d H:i:s', $_W['timestamp']);
				$content = array(
						'first' => array(
								'value' => '您好，您本月物业费已出。',
							),
						'userName' => array(
								'value' => $member['realname'],
							),
						'address' => array(
								'value' => $member['address'],
							),
						'pay'    => array(
								'value' => $cost['total'].'元',
							),
						'remark'    => array(
							'value' => '请尽快缴纳，如有疑问，请咨询.',
						),	
					);
				$result = $this->sendtpl($openid,$url,$template_id,$content);

			}
		}
	}
	//删除用户
	if (checksubmit('delete')) {
		$cids=$_GPC['cid'];
		if (!empty($cids)) {
			foreach ($cids as $key => $cid) {
				pdo_delete('xcommunity_cost_list',array('id' => $cid));
			}
			message('删除成功',referer(),'success');
		}
	}
	include $this->template('web/cost/detail');
}elseif ($op == 'setgoodsproperty') {
		$type = $_GPC['type'];
		$data = intval($_GPC['data']);

		if (in_array($type, array('status'))) {
			$data = ($data=='是'?'否':'是');
			pdo_update("xcommunity_cost_list", array($type => $data), array("id" => $id, "weid" => $_W['uniacid']));
			die(json_encode(array("result" => 1, "data" => $data)));
		}

		die(json_encode(array("result" => 0)));
}elseif($op == 'order'){
		//物业订单
		if (empty($id)) {
			message('缺少参数',referer(),'error');
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize  = 20;
		$condition = '';
		if($_GPC['mobile']){
			$condition .= "AND p.mobile='{$_GPC['mobile']}'";
		}
		if($_GPC['realname']){
			$condition .= "AND p.realname='{$_GPC['realname']}'";
		}
		$list = pdo_fetchall("SELECT o.* ,p.username as username ,p.mobile as mobile FROM".tablename('xcommunity_order')."as o left join (".tablename('xcommunity_cost_list')."as p left join ".tablename('xcommunity_cost')."as r on p.cid = r.id) on o.pid = p.id WHERE o.weid=:weid AND r.id = :id $condition LIMIT ".($pindex - 1) * $psize.','.$psize,array(':id' => $id,':weid' => $_W['weid']));
		$total =pdo_fetchcolumn("SELECT COUNT(*) FROM".tablename('xcommunity_order')."as o left join (".tablename('xcommunity_cost_list')."as p left join ".tablename('xcommunity_cost')."as r on p.cid = r.id) on o.pid = p.id  WHERE o.weid=:weid AND r.id = :id $condition",array(':id' => $id,':weid' => $_W['weid']));
		$pager  = pagination($total, $pindex, $psize);
		include $this->template('web/cost/order');
}elseif($op == 'del') {
		//物业费订单删除
		if ($_W['isajax']) {
			if (empty($id)) {
				exit('缺少参数');
			}
			$order = pdo_fetch("SELECT * FROM".tablename('xcommunity_order')."WHERE id=:id",array(':id' => $id));
			if (empty($order)) {
				exit('订单不存在');
			}
			$r = pdo_delete("xcommunity_order",array('id' => $id));
			if ($r) {
				$result = array(
						'status' => 1,
					);
				echo json_encode($result);exit();
			}
		}
}elseif ($op == 'edit') {
	//编辑用户
	if (empty($id)) {
		message('缺少参数',referer().'error');
	}
	$item = pdo_fetch("SELECT mobile,username,homenumber,total,id,propertyfee,otherfee,total,costtime FROM".tablename('xcommunity_cost_list')."WHERE id=:id",array(':id' => $id));
	if (empty($item)) {
		message('数据不存在或已被删除',referer(),'error');
	}
	if (checksubmit('submit')) {
		$data = array(
				'username' => $_GPC['username'],
				'mobile' => $_GPC['mobile'],
				'homenumber' => $_GPC['homenumber'],
				'costtime' => $_GPC['costtime'],
				'propertyfee' => $_GPC['propertyfee'],
				'otherfee' => $_GPC['otherfee'],
				'total' => $_GPC['total'],
			);
		if ($id) {
			$r = pdo_update('xcommunity_cost_list',$data,array('id' => $id));
			if ($r) {
				message('修改成功',referer(),'success');
			}
		}
	}
	include $this->template('web/cost/edit');
}










