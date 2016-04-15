<?php
	$members = pdo_fetchall("select id, realname, mobile from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and status = 1");
	$member = array();
	foreach($members as $m){
		$member['realname'][$m['id']] = $m['realname'];
		$member['mobile'][$m['id']] = $m['mobile'];
	}
	// 正在申请
	if($op=='display'){
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$mid = "select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission')." where weid = ".$_W['uniacid']." and flag = -1 and mid in (".$mid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission'). " where weid = ".$_W['uniacid']." and flag = -1 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_commission')." where weid = ".$_W['uniacid']." and flag = -1");
			$pager = pagination1($total, $pindex, $psize);
		}
		include $this->template('crediting');
		exit;
	}	if($op=='detail'){		$id = intval($_GPC['id']);		if($_GPC['opp']=='submit'){			pdo_update('hc_hunxiao_commission', array('content'=>trim($_GPC['content'])), array('id'=>$id));			message('提交成功！', $this->createWebUrl('credit'), 'success');		} else {			$detail = pdo_fetch("select * from ".tablename('hc_hunxiao_commission'). " where id = ".$id);			include $this->template('crediting_detail');			exit;		}	}	
	// 审核通过
	if($op=='applyed'){
		if($_GPC['opp']=='sort'){
			$sort = array(
				'realname'=>$_GPC['realname'],
				'mobile'=>$_GPC['mobile']
			);
			//$shareid = pdo_fetchall("select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'");
			$mid = "select id from ".tablename('hc_hunxiao_member')." where weid = ".$_W['uniacid']." and realname like '%".$sort['realname']."%' and mobile like '%".$sort['mobile']."%'";
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission'). " where weid = ".$_W['uniacid']." and flag = 2 and mid in (".$mid.") ORDER BY id desc");
			$total = sizeof($list);
		}else{
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$list = pdo_fetchall("select * from ".tablename('hc_hunxiao_commission')." where weid = ".$_W['uniacid']." and flag = 2 ORDER BY id DESC limit ".($pindex - 1) * $psize . ',' . $psize);
			$total = pdo_fetchcolumn("select count(id) from ".tablename('hc_hunxiao_commission'). " where weid = ".$_W['uniacid']." and flag = 2");
			$pager = pagination($total, $pindex, $psize);
		}
		include $this->template('credited');
		exit;
	}?>