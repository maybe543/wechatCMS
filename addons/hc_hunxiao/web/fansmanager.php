<?php
	// 经销商列表
	if($op=='display'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_member'). " where flag = 1 and weid = ".$_W['uniacid']." ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_hunxiao_member'). "where flag = 1 and weid =".$_W['uniacid']);;
		$pager = pagination($total, $pindex, $psize);
				
		$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('hc_hunxiao_commission')." where weid = ".$_W['uniacid']." and flag = 0 group by mid");
		// 还需结佣
		$commission = array();
		foreach($commissions as $c){
			$commission[$c['mid']] = $c['commission']*1000/1000;
		}
	}
	
	if($op=='nocheck'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_member'). " where flag = 0 and weid = ".$_W['uniacid']." ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_hunxiao_member'). "where flag = 0 and weid =".$_W['uniacid']);;
		$pager = pagination($total, $pindex, $psize);
		
		include $this->template('fansmanagered');
		exit;
	}
	// 查找经销商
	if($op=='sort'){
		$sort = array(
			'realname'=>$_GPC['realname'],
			'mobile'=>$_GPC['mobile']
		);
		if($_GPC['opp']=='nocheck'){
			$status = 0;
		} else {
			$status = 1;
		}
		// 符合条件的经销商
		$list = pdo_fetchall("select * from". tablename('hc_hunxiao_member')."where flag = ".$status." and weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
		$total = pdo_fetchcolumn("select count(id) from". tablename('hc_hunxiao_member')."where flag = ".$status." and weid =".$_W['uniacid'].".and realname like '%".$sort['realname']. "%' and mobile like '%".$sort['mobile']. "%' ORDER BY id DESC");
		$commissions = pdo_fetchall("select mid, sum(commission) as commission from ".tablename('hc_hunxiao_commission')." where weid = ".$_W['uniacid']." and flag = 0 group by mid");
		// 还需结佣
		$commission = array();
		foreach($commissions as $c){
			$commission[$c['mid']] = $c['commission'];
		}
		if($_GPC['opp']=='nocheck'){
			include $this->template('fansmanagered');
			exit;
		}
	}
	
	// 删除经销商
	if($op=='delete'){
		$temp = pdo_delete('hc_hunxiao_member', array('id'=>$_GPC['id']));
		if(empty($temp)){
			if($_GPC['opp']=='nocheck'){
				message('删除失败，请重新删除！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'error');
			} else {
				message('删除失败，请重新删除！', $this->createWebUrl('fansmanager'), 'error');
			}
		}else{
			if($_GPC['opp']=='nocheck'){
				message('删除成功！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'success');
			} else {
				message('删除成功！', $this->createWebUrl('fansmanager'), 'success');
			}
		}
	}
	
	// 经销商详情
	if($op=='detail'){
		$id = $_GPC['id'];
		$user = pdo_fetch("select * from ".tablename('hc_hunxiao_member'). " where id = ".$id);
		if($_GPC['opp']=='nocheck'){
			include $this->template('fansmanagered_detail');
		} else {
			include $this->template('fansmanager_detail');
		}
		exit;
	}
	
	// 设置经销商权限，类型
	if($op=='status'){
		$status = array(
			'status'=>$_GPC['status'],
			'flag'=>$_GPC['flag'],
			'content'=>trim($_GPC['content'])
		);
		$temp = pdo_update('hc_hunxiao_member', $status, array('id'=>$_GPC['id']));
		if(empty($temp)){
			if($_GPC['opp']=='nocheck'){
				message('设置用户权限失败，请重新设置！', $this->createWebUrl('fansmanager', array('op'=>'detail', 'opp'=>'nocheck', 'id'=>$_GPC['id'])), 'error');
			} else {
				message('设置用户权限失败，请重新设置！', $this->createWebUrl('fansmanager', array('op'=>'detail', 'id'=>$_GPC['id'])), 'error');
			}
		}else{
			if($_GPC['opp']=='nocheck'){
				message('设置用户权限成功！', $this->createWebUrl('fansmanager', array('op'=>'nocheck')), 'success');
			} else {
				message('设置用户权限成功！', $this->createWebUrl('fansmanager'), 'success');
			}
		}
	}
	
	// 充值
	if($op=='recharge'){
		$id = $_GPC['id'];
		$user = pdo_fetch("select * from ".tablename('hc_hunxiao_member'). " where id = ".$id);
		if($_GPC['opp']=='recharged'){
			if(!is_numeric($_GPC['commission'])){
				message('佣金请输入合法数字！', '', 'error');
			}
			$recharged = array(
				'weid'=>$_W['uniacid'],
				'mid'=>$id,
				'flag'=>1,
				'content'=>trim($_GPC['content']),
				'commission'=>$_GPC['commission'],
				'createtime'=>time()
			);
			$temp = pdo_insert('hc_hunxiao_commission', $recharged);
			// 已结佣金
			$commission = pdo_fetchcolumn("select commission from ".tablename('hc_hunxiao_member'). " where id = ".$id);
			
			if(empty($temp)){
				message('充值失败，请重新充值！', $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'error');
			}else{
				pdo_update('hc_hunxiao_member', array('commission'=>$commission+$_GPC['commission']), array('id'=>$id));
				message('充值成功！', $this->createWebUrl('fansmanager', array('op'=>'recharge', 'id'=>$_GPC['id'])), 'success');
			}
		}
		
		$commission = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where mid = ".$id." and flag = 0 and weid = ".$_W['uniacid']);
		$commission = empty($commission)?0:$commission;
		// 可结佣金
		$commission = ($commission*1000 - $user['commission']*1000)/1000;
		// 充值记录
		$commissions = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission')." where mid = ".$id." and weid = ".$_W['uniacid']." and flag = 1");
		include $this->template('fansmanager_recharge');
		exit;
	}
	
	$userdefaulttotal = pdo_fetchcolumn("select userdefault from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
	
	if($op=='lowfans'){
		if(empty($userdefaulttotal)){
			$userdefaulttotal = 1;
		}
		$fanslevel = array();
		$lowfansid = '('.intval($_GPC['id']).')';
		$lowfansids = array();
		for($i=1; $i<=$userdefaulttotal; $i++){
			$lowfansids[$i] = $lowfansid;
			$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$lowfansid." and status = 1 and id != '".$_GPC['id']."'");
			$lowfansid = '';
			if(!empty($fanslevel[$i])){
				foreach($fanslevel[$i] as $f){
					$lowfansid = $lowfansid.$f['id'].',';
				}
				$lowfansid = '('.trim($lowfansid, ',').')';
			} else {
				break;
			}
			if($i==$_GPC['level']){
				break;
			}
		}
		if(empty($lowfansids[$_GPC['level']])){
			$lowfansids = "(".'-1'.")";
		} else {
			$lowfansids = $lowfansids[$_GPC['level']];
		}
		$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$lowfansids." and status = 1");
	}
	if(intval($userdefaulttotal)>0){
		$fanslevel = array();
		for($i=1; $i<=$userdefaulttotal; $i++){
			$fanslevel[$i] = $i;
		}
	}
	
	include $this->template('fansmanager');
?>