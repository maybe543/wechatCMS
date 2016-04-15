<?php
	
	
	if($_GPC['openid']!=""){
	  $openid = $_GPC['openid'];
	  $status = $_GPC['showstatus'];
	  $id = $_GPC['id'];
	
	  $arr = array(
	    'q_user'=>$openid,
	    'id'=>$id,
	  
	  );
	  $data['showstatus']=1;
	  pdo_update('moneygo_goodslist',$data,$arr);
	  
	  message('晒单成功',$this->createMobileurl('myorder'),'success');
	  
	}
	else{
		
    $ar = pdo_fetchall("SELECT * FROM " . tablename('moneygo_goodslist') . " WHERE uniacid = '{$_W['uniacid']}'and status =1 and showstatus=1 ORDER BY createtime DESC ");

		
		
	}
	include $this->template('show');

	
	