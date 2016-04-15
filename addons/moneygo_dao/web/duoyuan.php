<?php
	
	
	
	global $_W,$_GPC;
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	
	if($op=='display'){
		
		
		$moshi = pdo_fetchall("SELECT * FROM".tablename('moneygo_moshi')."WHERE uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
		
		
		
		
		
		include $this->template('duoyuan'); 
	}
	else if($op=="edit"){
		
		
	    $id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('moneygo_moshi').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
			$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
			$moshi = pdo_fetch($sql, $params);
			
			if(empty($moshi)){
				message('没有相关模式.', $this->createWebUrl('duoyuan'));
			}
		}
		
		if(checksubmit('submit')){
		
		
		   
				$data = array(
				
				   'shuzi'=>trim($_GPC['shuzi']),
				   'bg'=>$_GPC['bg'],
				   'sy'=>$_GPC['sy'],
				   'uniacid'=>$_W['uniacid']
				
				
				);
				
				if(!empty($id)){
					
					pdo_update('moneygo_moshi',$data,array('id'=>$id));
				}else{
					
					pdo_insert('moneygo_moshi',$data);
				}
		 	message('数据更新成功！', $this->createWebUrl('duoyuan',array('op' => 'display')), 'success');
	
		}
				

		
		
		
		include $this->template('duoyuan'); 
	}

	else if($op=="del"){
	
		
		$id = intval($_GPC['id']);
		
		$res = pdo_delete('moneygo_moshi',array('id'=>$id));
		if($res){
			
			message('删除模式成功', $this->createWebUrl('duoyuan',array('op' => 'display')), 'success');
		}else{
			
			message('删除模式失败', $this->createWebUrl('duoyuan',array('op' => 'display')), 'error');
		}
		
	}