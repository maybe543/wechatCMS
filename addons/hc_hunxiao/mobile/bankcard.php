<?php
		$from_user = $_W['openid'];	
	
		$rule = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_rules')." WHERE `weid` = :weid ",array(':weid' => $weid));	
		if(empty($from_user)){
			message('你想知道怎么加入么?',$rule['gzurl'],'sucessr');
			exit;
		}
		
		$profile= pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
		$id = $profile['id'];
		if(intval($profile['id']) && $profile['status']==0){
			include $this->template('forbidden');
			exit;
		}
		if(empty($profile)){
			message('请先注册',$this->createMobileUrl('register'),'error');
			exit;
		}
		if($op=='edit'){
			$data=array(
				'mobile'=>$_GPC['mobile'],
				'bankcard'=>$_GPC['bankcard'],
				'banktype'=>$_GPC['banktype'],
				'alipay'=>$_GPC['alipay'],
				'wxhao'=>$_GPC['wxhao']
			);
			if(!empty($data['bankcard']) && !empty($data['banktype'])){
				pdo_update('hc_hunxiao_member',$data,array('from_user' => $from_user));
				if($_GPC['opp']=='complated'){
					echo 3;
					exit;
				}
				echo 1;
				
			}else{
				echo 0;
			}
			exit;
		}

		include $this->template('bankcard');

?>