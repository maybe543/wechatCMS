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
	
	if($op=='display'){
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow) && $follow['follow']){
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			$fcredit = $fcredit['credit1']*100/100;
		} else {
			$fcredit = 0;
		}
		$rule = pdo_fetch("select conversion, gzurl from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		$creditlog = pdo_fetchall("select * from ".tablename('hc_hunxiao_credit')." where status = 0 and mid = ".$id." and weid = ".$weid." order by createtime desc");
		$goods = pdo_fetchall("select id, title from ".tablename('hc_hunxiao_goods')." where weid = ".$weid);
		$good = array();
		foreach($goods as $g){
			$good[$g['id']] = $g['title'];
		}
	}
	

	// 处理申请
	if($op=='applyed'){
		if($profile['flag']==0){
			echo -1;
			exit;
		}
		$credit = intval($_GPC['credit']);
		if(!is_numeric($credit) && $credit<=0){
			echo -2;
			exit;
		}
		$follow = pdo_fetch("select uid, follow from ".tablename('mc_mapping_fans')." where uniacid = ".$weid." and openid = '".$_W['openid']."'");
		if(!empty($follow) && $follow['follow']){
			$fcredit = mc_fetch($follow['uid'], array('credit1'));
			if($credit > $fcredit['credit1']){
				echo -3;
				exit;
			}
			pdo_update('mc_members', array('credit1'=>$fcredit['credit1']-$credit), array('uid'=>$follow['uid']));
		} else {
			echo -4;
			exit;
		}
		$rule = pdo_fetch("select conversion from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		if(intval($rule['conversion'])){
			$credit = sprintf("%.2f", $credit/$rule['conversion']); 
		}
		pdo_update('hc_hunxiao_member', array('commission'=>$profile['commission']+$credit), array('id'=>$id));
		$commissionlog = array(
			'weid'=>$weid,
			'mid'=>$id,
			'ogid'=>0,
			'commission'=>$credit,
			'flag'=>0,
			'isout'=>0,
			'createtime'=>time()
		);
		pdo_insert('hc_hunxiao_commission', $commissionlog);
		$commissionlog['flag']=-1;
		pdo_insert('hc_hunxiao_commission', $commissionlog);
		echo 1;
		exit;
	}
	
	include $this->template('creditapply');
?>