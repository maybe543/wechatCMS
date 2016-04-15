<?php
	$members = pdo_fetchall("select id, realname, mobile from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and status = 1");
	$member = array();
	foreach($members as $m){
		$member['realname'][$m['id']] = $m['realname'];
		$member['mobile'][$m['id']] = $m['mobile'];
	}	$goods = pdo_fetchall("select id, title, marketprice from ".tablename('hc_hunxiao_goods'). " where weid = ".$_W['uniacid']. " and status = 1");	$good = array();	foreach($goods as $g){		$good['title'][$g['id']] = $g['title'];		$good['price'][$g['id']] = $g['marketprice'];	}
	// 正在申请
	if($op=='display'){
		if($_GPC['opp']=='check'){
			$shareid = $_GPC['shareid'];
			// 申请人的信息
			$user = pdo_fetch("select realname, mobile from ".tablename('hc_hunxiao_member')." where id = ".$_GPC['shareid']);
			// 申请订单信息
			$info = pdo_fetch("select * from ".tablename('hc_hunxiao_memberrelative')." where id = ".$_GPC['id']);
			include $this->template('applying_detail');
			exit;
		}
		if($_GPC['opp']=='checked'){
			$checked = array(
				'status'=>$_GPC['status'],
				'checktime'=>time(),
				'content'=>trim($_GPC['content'])
			);
			$temp = pdo_update('hc_hunxiao_memberrelative', $checked, array('id'=>$_GPC['id']));
			if(empty($temp)){
				message('审核失败，请重新审核！', $this->createWebUrl('commission', array('opp'=>'check', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
			}else{
				message('审核成功！', $this->createWebUrl('commission'), 'success');
			}
		}
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$shareid = "select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative')." where weid = ".$_W['uniacid']." and status = 1 and shareid in (".$shareid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = 1 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_memberrelative')." where weid = ".$_W['uniacid']." and status = 1");
			$pager = pagination1($total, $pindex, $psize);
		}
		include $this->template('applying');
		exit;
	}
	// 审核通过
	if($op=='applyed'){
		if($_GPC['opp']=='jieyong'){
			$shareid = $_GPC['shareid'];
			// 申请人的信息
			$user = pdo_fetch("select id, realname, mobile from ".tablename('hc_hunxiao_member')." where id = ".$_GPC['shareid']);
			// 申请订单信息
			$info = pdo_fetch("select * from ".tablename('hc_hunxiao_memberrelative')." where id = ".$_GPC['id']);
			// 佣金记录
			$commissions = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission')." where ogid = ".$_GPC['id']);
			$commission = pdo_fetchcolumn("select sum(commission) from ".tablename('hc_hunxiao_commission')." where ogid = ".$_GPC['id']);
			$commission = empty($commission)?0:$commission;
			include $this->template('applyed_detail');
			exit;
		}
		if($_GPC['opp']=='jieyonged'){
			if($_GPC['status']==2){
				if(!is_numeric($_GPC['commission'])){
					message('佣金请输入合法数字！', '', 'error');
				}
				$shareid = $_GPC['shareid'];
				$ogid = $_GPC['id'];
				$commission = array(
					'weid'=>$_W['uniacid'],
					'mid'=>$shareid,
					'ogid'=>$ogid,
					'commission'=>$_GPC['commission'],
					'content'=>trim($_GPC['content']),
					'createtime'=>time()
				);
				$temp = pdo_insert('hc_hunxiao_commission', $commission);
				$user = pdo_fetch("select from_user from ".tablename('hc_hunxiao_member')." where id = ".$_GPC['shareid']);
				$info = pdo_fetch("select applytime, checktime from ".tablename('hc_hunxiao_memberrelative')." where id = ".$_GPC['id']);
				pdo_update('hc_hunxiao_memberrelative', array('isjieyong'=>1), array('id'=>$_GPC['id']));
				sendCheckChange($user['from_user'], $_GPC['commission'], date('Y-m-d h:i:s', $info['applytime']), date('Y-m-d h:i:s', $info['checktime']));
				if(empty($temp)){
					message('充值失败，请重新充值！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
				}else{
					message('充值成功！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'success');
				}
			}else{
				$checked = array(
					'status'=>$_GPC['status'],
					'content'=>trim($_GPC['content'])
				);
				$temp = pdo_update('hc_hunxiao_memberrelative', $checked, array('id'=>$_GPC['id']));
				if(empty($temp)){
					message('提交失败，请重新提交！', $this->createWebUrl('commission', array('op'=>'applyed', 'opp'=>'jieyong', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
				}else{
					message('提交成功！', $this->createWebUrl('commission', array('op'=>'applyed')), 'success');
				}
			}
		}
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$shareid = "select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = 2 and shareid in (".$shareid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative')." where weid = ".$_W['uniacid']." and status = 2 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = 2");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('applyed');
		exit;
	}
	// 审核无效
	if($op=='invalid'){
		if($_GPC['opp']=='delete'){
			$delete = array(
				'status'=>-2
			);
			$temp = pdo_update('hc_hunxiao_memberrelative', $delete, array('id'=>$_GPC['id']));
			if(empty($temp)){
				message('删除失败，请重新删除！', $this->createWebUrl('commission', array('op'=>'invalid')), 'error');
			}else{
				message('删除成功！', $this->createWebUrl('commission', array('op'=>'invalid')), 'success');
			}
		}
		if($_GPC['opp']=='detail'){
			$shareid = $_GPC['shareid'];
			// 申请人的信息
			$user = pdo_fetch("select realname, mobile from ".tablename('hc_hunxiao_member')." where id = ".$_GPC['shareid']);
			// 申请订单信息
			$info = pdo_fetch("select * from ".tablename('hc_hunxiao_memberrelative')." where id = ".$_GPC['id']);
			include $this->template('invalid_detail');
			exit;
		}
		if($_GPC['opp']=='invalided'){
			$invalided = array(
				'status'=>$_GPC['status'],
				'content'=>trim($_GPC['content'])
			);
			$temp = pdo_update('hc_hunxiao_memberrelative', $invalided, array('id'=>$_GPC['id']));
			if(empty($temp)){
				message('提交失败，请重新提交！', $this->createWebUrl('commission', array('op'=>'invalid', 'opp'=>'detail', 'shareid'=>$_GPC['shareid'], 'id'=>$_GPC['id'])), 'error');
			}else{
				message('提交成功！', $this->createWebUrl('commission', array('op'=>'invalid')), 'success');
			}
		}
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$shareid = "select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = -1 and shareid in (".$shareid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = -1 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$pager = pagination1($total, $pindex, $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_memberrelative'). " where weid = ".$_W['uniacid']." and status = -1");
		}
		include $this->template('invalid');
		exit;
	}
?>