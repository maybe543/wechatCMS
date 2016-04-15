<?php
	$theone = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_rules')." WHERE  weid = :weid" , array(':weid' => $_W['uniacid']));
	$commissions = pdo_fetchall('SELECT * FROM '.tablename('hc_hunxiao_userdefault')." WHERE  weid = :weid order by userdefault asc" , array(':weid' => $_W['uniacid']));
	$id = $theone['id'];
	if (checksubmit('submit')) {
		$commtime = $_GPC['commtime'];
		$promotertimes = $_GPC['promotertimes'];
		$userdefault = $_GPC['userdefault'];
		$commission = $_GPC['commission'];
		$conversion = $_GPC['conversion'];
		if(!is_numeric($commtime)){
			message('请输入合法数字周期！');
		}
		$credit1 = intval($_GPC['credit1']);
		$credit2 = intval($_GPC['credit2']);
		if(empty($credit1)){
			$credit1 = 0;
		} else {
			$credit1 = is_numeric($credit1) ? $credit1 : message('请输入合法的积分数');
		}
		if(empty($credit2)){
			$credit2 = 0;
		} else {
			$credit2 = is_numeric($credit2) ? $credit2 : message('请输入合法的积分数');
		}
		if($credit1<$credit2){
			$credit = $credit1.','.$credit2;
		} else {
			$credit = $credit2.','.$credit1;
		}
		if(empty($conversion)){
			$conversion = 0;
		} else {
			$conversion = is_numeric($conversion) ? $conversion : message('请输入合法的积分兑换比例');
		}

		$promotertimes = is_numeric($promotertimes)?$promotertimes:message('请输入合法次数！');
		
		
		if(empty($userdefault)){
			$userdefault = 1;
		} else {
			$userdefault = is_numeric($userdefault)?$userdefault:message('请输入合法分销级数！');
		}
		if(!empty($commission)){
			pdo_delete('hc_hunxiao_userdefault', array('weid'=>$weid));
			foreach($commission as $key=>$c){
				$key++;
				if(empty($c)){
					$c = 0;
				} else {
					$c = is_numeric($c)?$c:message('请输入合法佣金比例！');
				}
				pdo_insert('hc_hunxiao_userdefault', array('weid'=>$_W['uniacid'], 'commission'=>$c, 'userdefault'=>$key, 'createtime'=>time()));
			}
		}
		$insert = array(
			'weid' => $_W['uniacid'],
			'rule' => htmlspecialchars_decode($_GPC['rule']),
			'terms' => htmlspecialchars_decode($_GPC['terms']),
			'commtime' => $commtime,
			'credit' => $credit,
			'conversion' => $conversion,
			'title' => $_GPC['title'],
			'picture' => $_GPC['picture'],
			'qrpicture' => $_GPC['qrpicture'],
			'description' => $_GPC['description'],
			//'online' => trim($_GPC['online']),
			//'onlinepicture' => $_GPC['onlinepicture'],
			'isrecommend' => $_GPC['isrecommend'],
			'globalCommission' => $_GPC['globalCommission'],
			'gzurl' => $_GPC['gzurl'],
			'promotertimes' => $promotertimes,
			'userdefault' => $userdefault,
			'createtime' => TIMESTAMP
		);
		if(empty($id)) {
			pdo_insert('hc_hunxiao_rules', $insert);
			!pdo_insertid() ? message('保存失败, 请稍后重试.','error') : '';
		} else {
			if(pdo_update('hc_hunxiao_rules', $insert,array('id' => $id)) === false){
				message('更新失败, 请稍后重试.','error');
			}
		}
		message('更新成功！', $this->createWebUrl('rules'), 'success');
	}
	$credit = explode(",", $theone['credit']);
	$theone['credit1'] = $credit[0];
	$theone['credit2'] = $credit[1];
	include $this->template('rules');

?>