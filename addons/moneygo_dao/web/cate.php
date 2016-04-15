<?php
	
	
	
	global $_W,$_GPC;
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	
	if($op=='display'){
		
		
		$cate = pdo_fetchall("SELECT * FROM".tablename('moneygo_cate')."WHERE uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
		
		include $this->template('cate'); 
	}
	else if($op=="edit"){
		
		
	    $id = intval($_GPC['id']);
		if(!empty($id)){
			$sql = 'SELECT * FROM '.tablename('moneygo_cate').' WHERE id=:id AND uniacid=:uniacid LIMIT 1';
			$params = array(':id'=>$id, ':uniacid'=>$_W['uniacid']);
			$cate = pdo_fetch($sql, $params);
			
			if(empty($cate)){
				message('没有分类.', $this->createWebUrl('cate'));
			}
		}
		
		if(checksubmit('submit')){
		
		
		   
				$data = array(
				
				   'name'=>trim($_GPC['name']),
				   'tu'=>$_GPC['tu'],
				  
				   'uniacid'=>$_W['uniacid']
				
				
				);
				
				if(!empty($id)){
					
					pdo_update('moneygo_cate',$data,array('id'=>$id));
				}else{
					
					pdo_insert('moneygo_cate',$data);
				}
		 	message('数据更新成功！', $this->createWebUrl('cate',array('op' => 'display')), 'success');
	
		}

		include $this->template('cate'); 
	}

			else if($op=="del"){
		
		$id = intval($_GPC['id']);
		
		$res = pdo_delete('moneygo_cate',array('id'=>$id));
		if($res){
			
			message('删除分类成功', $this->createWebUrl('cate',array('op' => 'display')), 'success');
		}else{
			
			message('删除分类失败', $this->createWebUrl('cate',array('op' => 'display')), 'error');
		}
		
	}