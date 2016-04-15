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
		$userdefault = pdo_fetchcolumn("select userdefault from ".tablename('hc_hunxiao_rules')." where weid = ".$weid);
		if(empty($userdefault)){
			$userdefault = 1;
		}
		$fanslevel = array();
		$lowfansid = '('.$id.')';
		$lowfansids = array();
		for($i=1; $i<=$userdefault; $i++){
			$lowfansids[$i] = $lowfansid;
			$fanslevel[$i] = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$lowfansid." and status = 1");
			$lowfansid = '';
			if(!empty($fanslevel[$i])){
				foreach($fanslevel[$i] as $f){
					$lowfansid = $lowfansid.$f['id'].',';
				}
				$lowfansid = '('.trim($lowfansid, ',').')';
			} else {
				unset($fanslevel[$i]);
				break;
			}
		}
	}

	if($op=='more'){
		$level = $_GPC['level'];
		$fanslevel = pdo_fetchall("select * from ".tablename('hc_hunxiao_member')." where shareid in ".$_GPC['lowfansids']." and status = 1");
	}
	
	include $this->template('myfans');
?>