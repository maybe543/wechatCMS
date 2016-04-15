<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if(intval($profile['id']) && $profile['status']==0){
		include $this->template('forbidden');
		exit;
	}
	if(empty($profile)){
		message('请先注册',$this->createMobileUrl('register'),'error');
		exit;
	}
	$gzurl = pdo_fetch("select description from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
	if($op=='display'){
		// 总佣金
		$commissioning = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where flag = 0 and mid = ".$profile['id']." and weid = ".$weid);
		$commissioning = empty($commissioning)?0:$commissioning;
		// 已结佣
		$commissioned = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where (flag = 1 or flag = 2) and mid = ".$id." and weid = ".$weid);
		$commissioned = empty($commissioned)?0.00:$commissioned;
		// 可结佣
		$commissioning = $commissioning - $commissioned;
		$total = pdo_fetchcolumn("select count(id) from ". tablename('hc_hunxiao_commission'). " where mid =". $profile['id']. " and flag = 0");
		if($_GPC['opp'] == 'more'){
			$opp = 'more';
			$pindex = max(1, intval($_GPC['page']));
			$psize = 15;
			// 账户充值记录
			$list = pdo_fetchall("select co.commission, co.createtime, og.orderid, og.goodsid, og.total from ". tablename('hc_hunxiao_commission'). " as co left join ".tablename('hc_hunxiao_memberrelative')." as og on co.ogid = og.id and co.weid = og.weid where co.mid =". $profile['id']. " and co.flag = 0 ORDER BY co.createtime DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$pager = pagination1($total, $pindex, $psize);
		}else{
			// 账户充值记录
			$list = pdo_fetchall("select co.commission, co.createtime, og.orderid, og.goodsid, og.total from ". tablename('hc_hunxiao_commission'). " as co left join ".tablename('hc_hunxiao_memberrelative')." as og on co.ogid = og.id and co.weid = og.weid where co.mid =". $profile['id']. " and co.flag = 0 ORDER BY co.createtime DESC limit 10");
		}
		$addresss = pdo_fetchall("select id, realname from ".tablename('hc_hunxiao_address')." where weid = ".$weid);
		$address = array();
		foreach($addresss as $adr){
			$address[$adr['id']] = $adr['realname'];
		}
		$goods = pdo_fetchall("select id, title from ".tablename('hc_hunxiao_goods')." where weid = ".$weid);
		$good = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
	}
	
	// 申请佣金
	if($op=='commapply'){
		
		// 提现周期
		$commtime = pdo_fetch("select commtime, promotertimes from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		if(empty($commtime) && $commtime['commtime']<0){
			message("此功能还未开放，请耐心等待...");
		}
		$moneytime = time()-3600*24*$commtime['commtime'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 15;
		$list = pdo_fetchall("SELECT * FROM " .tablename('hc_hunxiao_memberrelative')." WHERE shareid = ".$id." and weid = ".$weid." and status = 0 and flag >= 3 and commission > 0 and createtime < ".$moneytime." ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("SELECT count(id) FROM " .tablename('hc_hunxiao_memberrelative')." WHERE shareid = ".$id." and weid = ".$weid." and status = 0 and flag >= 3 and createtime < ".$moneytime);
		if($profile['flag']==0){
			if($total>=$commtime['promotertimes']){
				pdo_update('hc_hunxiao_member', array('flag'=>1), array('id'=>$profile['id']));
				$profile['flag'] = 1;
			}
		}
		$pager = pagination1($total, $pindex, $psize);
		$goods = pdo_fetchall("select id, title from ".tablename('hc_hunxiao_goods'). " where weid = ".$weid. " and status = 1");
		$good = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
		include $this->template('commapply');
		exit;
	}
	// 处理申请
	if($op=='applyed'){
		if($profile['flag']==0){
			message('申请佣金失败！');
		}
		$isbank = pdo_fetch("select id, bankcard, banktype from ".tablename('hc_hunxiao_member')." where weid = ".$weid." and from_user = '".$_W['openid']."'");
		if(empty($isbank['bankcard']) || empty($isbank['banktype'])){
			message('请先完善银行卡信息！', $this->createMobileUrl('bankcard', array('id'=>$isbank['id'], 'opp'=>'complated')), 'error');
		}
		$update = array(
			'status'=>1,
			'applytime'=>time()
		);
		// 申请订单ID数组
		$selected = explode(',',trim($_GPC['selected']));
		for($i=0; $i<sizeof($selected); $i++){
			$temp = pdo_update('hc_hunxiao_memberrelative', $update, array('id'=>$selected[$i]));
		}
		if(!$temp){
			message('申请失败，请重新申请！', $this->createMobileUrl('commission', array('op'=>'commapply')), 'error');
		}else{
			message('申请成功！', $this->createMobileUrl('commission'), 'success');
		}
	}
	
	include $this->template('commission');
?>