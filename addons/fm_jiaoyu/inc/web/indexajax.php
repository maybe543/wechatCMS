<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */global $_W, $_GPC;
   $operation = in_array ( $_GPC ['op'], array ('default','checkpass','guanli') ) ? $_GPC ['op'] : 'default';

    if ($operation == 'default') {
	           die ( json_encode ( array (
			         'result' => false,
			         'msg' => '你是傻逼吗'
	                ) ) );
              }			
	if ($operation == 'checkpass') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schooid'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where :id = id And :weid = weid And :password = password", array(
		         ':id' => $_GPC ['schooid'],
				 ':weid' => $_GPC ['weid'],
				 ':password'=>$_GPC ['password']
				  ), 'id');
				  
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '密码输入错误！' 
		          ) ) );
		}else{  			
			$data ['result'] = true;
			
			$data ['url'] = $_W['siteroot'] .'web/'.$this->createWebUrl('assess', array('id' => $_GPC ['schooid'], 'schoolid' =>  $_GPC ['schooid']));
			
			$data ['msg'] = '密码正确！';

		 die ( json_encode ( $data ) );
		}
    }
	if ($operation == 'guanli') {
		$data = explode ( '|', $_GPC ['json'] );
		if (! $_GPC ['schooid1'] || ! $_GPC ['weid']) {
               die ( json_encode ( array (
                    'result' => false,
                    'msg' => '非法请求！' 
		               ) ) );
	         }
		
		$tid = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where :id = id And :weid = weid And :password = password", array(
		         ':id' => $_GPC ['schooid1'],
				 ':weid' => $_GPC ['weid'],
				 ':password'=>$_GPC ['password1']
				  ), 'id');
				  
		if(empty($tid['id'])){
			     die ( json_encode ( array (
                 'result' => false,
                 'msg' => '密码输入错误！' 
		          ) ) );
		}else{  			
			$data ['result'] = true;
			
			$data ['url'] = $_W['siteroot'] .'web/'.$this->createWebUrl('school', array('id' => $_GPC ['schooid1'], 'schoolid' =>  $_GPC ['schooid1'], 'op' => 'post'));
			
			$data ['msg'] = '密码正确！';

		 die ( json_encode ( $data ) );
		}
    }
	
?>