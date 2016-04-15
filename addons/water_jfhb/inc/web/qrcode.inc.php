<?php

   //对关注用户增加他的关注金额
  function add_money($param,$settings){
  	if (empty($settings['gz_min_amount'])){
  	  return;
  	}
    $openid=$param['fromuser'];
    $sql="update ".tablename("jfhb_user").
           " set wtx_money=wtx_money+{$settings['gz_min_amount']} ,money=money+{$settings['gz_min_amount']},user_jl=1".
       " where uniacid=".$param['uniacid']." and openid='$openid' ";
       $temp=pdo_query($sql);
       if ($temp){
         post_send_text($openid,"你获得了老用户奖励红包".$settings['gz_min_amount'].'元');
         return true;
       } else {
       	 return false;
       }
       
  }
  
    //对关注用户增加他的关注金额
  function send_jlinfo($param,$settings){
  	if (empty($settings['gz_min_amount'])){
  	  return;
  	}
    $openid=$param['fromuser'];
    $sql="update ".tablename("jfhb_user").
           " set user_jl=3".
       " where uniacid=".$param['uniacid']." and openid='$openid' and user_jl=2 ";
       $temp=pdo_query($sql);
      
       if ($temp){
         post_send_text($openid,"你获得了老用户奖励红包".$settings['gz_min_amount'].'元');
         return true;
       } else {
       	 return false;
       }
       
       
  }
  
     //对关注用户增加他的关注金额
  function add_money1($param,$settings){
  	if (empty($settings['gz_min_amount'])){
  	  return;
  	}
    $openid=$param['fromuser'];
    $sql="update ".tablename("jfhb_user").
           " set wtx_money=wtx_money+{$settings['gz_min_amount']} ,money=money+{$settings['gz_min_amount']},user_jl=1".
       " where uniacid=".$param['uniacid']." and openid='$openid' and money=0 and wtx_money=0";
       $temp=pdo_query($sql);
       if ($temp){
         post_send_text($openid,"你获得了老用户奖励红包".$settings['gz_min_amount'].'元');
       }
  }
  



        global $_W, $_GPC;
  		load()->func('tpl');
  		$op = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
  		$uniacid=$_W["uniacid"];
  		$acid=$_W["acid"];
  		$id=$_GPC['id'];
 	
  		$keyword=$_GPC['keyword'];
  	    
  		
  		if ($op=='display') {
  			$con="";	
  			$haibao_id=$_GPC['haibao_id'];
  		    $openid=$_GPC['openid'];
  			if (!empty($haibao_id)){
  			  $con.=" and haibao_id=$haibao_id";	
  			}
  			
  			if (!empty($openid)){
  			  $con.=" and openid='$openid'";	
  			}
  			
  			if (!empty($keyword)){
  				 $con.=" and nickname like '%$keyword%'";
  			}
  			
  			$pindex = max(1, intval($_GPC['page']));	
			$psize= 20;
			

             
            $list = pdo_fetchall("SELECT * from ".
			tablename('jfhb_qrcode')." a  where a.uniacid=$uniacid $con  LIMIT ". ($pindex -1) * $psize . ',' .$psize,
             array(),'openid');
			
		    if (!empty($list)){
	          $sql1 = 'SELECT * FROM ' . tablename('jfhb_haibao') . " WHERE `uniacid`=$uniacid";
	     	  $haibao = pdo_fetchall($sql1, array(),"id");
            }
			
			$total = pdo_fetchcolumn("SELECT COUNT(*)  from ".tablename('jfhb_qrcode')."  where uniacid=$uniacid $con");
			$pager = pagination($total, $pindex, $psize);
  		}
  		if ($op=='post') {
  			if (!empty($id)) {
  			$item = pdo_fetch("SELECT *  from ".tablename('jfhb_qrcode')."  where uniacid=$uniacid and id=$id");
  			if (empty($item)) {
					message('抱歉，用户信息不存在或是已经删除！', '', 'error');
				}
			}
			if (checksubmit('submit')) {
				$data = array(
				    'uniacid' => $_W['uniacid'],
		
					'qr_img' => $_GPC['qr_img'],
					'status' => $_GPC['status'],
					'createtime' => time()
				);
				if (!empty($id)) {			
					pdo_update('jfhb_qrcode', $data, array('id' => $id));
				}
				else{
					pdo_insert('jfhb_qrcode', $data);
				}
				message('更新海报用户成功', $this->createWebUrl('qrcode'), 'success');
				}
			} 
			if ($op=='delete') {
				pdo_delete('jfhb_qrcode', array('id' => $id));
        	    message('删除成功！', $this->createWebUrl('qrcode', array('op' => 'display')), 'success');
			}
    	if ($op=='delete_all') {
    	       pdo_delete('jfhb_qrcode', array('uniacid' => $uniacid));
        	   message('删除成功！', $this->createWebUrl('qrcode', array('op' => 'display')), 'success');
   		 }
   		 
   		 if ($op=='sendimage') {  	
   		   require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
   		   $sendtype=$_GPC['sendtype'];	 	
   		   load()->classs('weixin.account');
   		   $acid=!empty($_W['acid'])?$_W['acid']:$_W['uniacid'];
   		 
           $accObj= WeixinAccount::create($acid);
           $token=$accObj->fetch_token(); 
   		   $item = pdo_fetch("SELECT *  from ".tablename('jfhb_qrcode')."  where uniacid=$uniacid and id=$id");
   		   if (!empty($item['media_id']) && $sendtype=="image"){
             $result=sendImage($token,array('openid'=>$item['openid'],'media_id'=>$item['media_id']));
           } else {
   		   	 $target_file_url=tomedia($item['qr_img']);
             $result=post_send_text($item['openid'], "<a href='$target_file_url'>【点击这里查看您的专属海报】</a>");
           }
   		   if ($result['errcode']!=0){
             message('发送失败！'.json_encode($result));
           } else{
   		     message('发送成功！',referer(),'success');
   		   }
   		 }
   		 
   		  if ($op=='sendText') {  	
   		   	   $item = pdo_fetch("SELECT *  from ".tablename('jfhb_qrcode')."  where  id=$id");
   		   $url =$_W['siteroot']."app/index.php?i={$_W["weid"]}&c=entry&m={$this->modulename}&do=RespondText&username={$item['openid']}";
        
           load()->func('communication');                      
	       $ret=ihttp_request($url, $post = '', $extra = array());	
	       $errcode=json_decode($ret['content'],true);
	       if (is_error($ret) || ($errcode['errcode']!=1)){
	      	  message('错误信息'.json_encode($ret['content']));
	       } else {
	       	 message('生成成功',referer(), 'success');
	       }
   		 }
   		 
   		 if ($op=='genimage'){
   		   $item = pdo_fetch("SELECT *  from ".tablename('jfhb_qrcode')."  where  id=$id");
   		   $url =$_W['siteroot']."app/index.php?i={$_W["weid"]}&c=entry&m={$this->modulename}&do=RespondImage&username={$item['openid']}";
        
           load()->func('communication');                      
	       $ret=ihttp_request($url, $post = '', $extra = array());	
	       $errcode=json_decode($ret['content'],true);
	       if (is_error($ret) || ($errcode['errcode']!=1)){
	      	  message('错误信息'.json_encode($ret['content']));
	       } else {
	       	 message('生成成功',referer(), 'success');
	       }
   		 }
   		 
   		 
   		 if ($op=='gz_money'){
           require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
           ignore_user_abort(true);
           $openid=$_GPC['openid'];
           $settings=$this->module['config']; 	
           $param=array("fromuser"=>$openid,'acid'=>$acid,'uniacid'=>$uniacid);  
            $temp=add_money($param,$settings);
            if ($temp==true){
               message("发用户发红包成功",referer(), 'success');
            } else {
            	 message("失败",referer(), 'success');
            }
 
	      	  
	       
   		 }
   		 
   		 if ($op=="all2"){
 
   		 	$sql="select count(1) from ims_jfhb_user where uniacid=$uniacid and user_jl=2";       
           //  $sql=" select a.openid,a.uniacid from ims_jfhb_qrcode a inner join ims_jfhb_user  b on a.openid=b.openid and a.uniacid=b.uniacid ";
              $count = pdo_fetchcolumn($sql);
              message("还没发消息用户数".$count, '','success');
   		 }
   		 
   		  if ($op=="all"){
   		 	$sql=" update  ims_jfhb_user  a join (select d.openid from ims_jfhb_qrcode d) ".
            " z on a.openid=z.openid set wtx_money=wtx_money+0.58,money=money+0.58,user_jl=2 where money=0 and wtx_money=0";
            $sql1="select count(d.openid) from ims_jfhb_qrcode d inner join ims_mc_mapping_fans b".
           " on d.openid=b.openid where d.uniacid=$uniacid and b.uniacid=$uniacid  and follow=1 and unix_timestamp('2016-01-01 10:00')>followtime";
            
              $count = pdo_fetch($sql1);
              exit(strval($count));
   		 }
   		 
   		  if ($op=="all1"){
   		  	set_time_limit(0);
   		  	ignore_user_abort(true);
   		   require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
           $settings=$this->module['config']; 	
           	$_GPC['num']=empty($_GPC['num'])?500:$_GPC['num'];
   		 	$sql="SELECT openid  from ".tablename('jfhb_user')." where uniacid=$uniacid and user_jl=2 limit {$_GPC['num']}";
           $item = pdo_fetchall($sql);
            $count=count($item);
           foreach ($item as $value){          	
             $param=array("fromuser"=>$value['openid'],'uniacid'=>$uniacid);
          
           	 send_jlinfo($param,$settings);
           }
           
              message($count."个老用户发红包成功","", 'success');
      
   		 }
   		 
   		 
 
   		 
   		 if ($op=='user_jl'){
           require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
           ignore_user_abort(true);
           $settings=$this->module['config'];
           $sql="select a.openid from ".tablename("jfhb_user").
           " a inner join ".tablename("mc_mapping_fans").
           " b on a.openid=b.openid where a.uniacid=$uniacid " .
           "and b.uniacid=$uniacid and user_jl=0 and follow=1 and unix_timestamp('2016-01-01 10:00')>followtime order by a.id desc  limit 100 ";
         
         
           $totalsql="select count(a.openid) from ".tablename("jfhb_user").
           " a inner join ".tablename("mc_mapping_fans").
           " b on a.openid=b.openid where a.uniacid=$uniacid " .
           "and b.uniacid=$uniacid and user_jl=0 and follow=1 and unix_timestamp('2016-01-01 10:00')>followtime ";
         
           $item = pdo_fetchall($sql);
           
           $total = pdo_fetchcolumn($totalsql);
           $count=count($item);
           foreach ($item as $value){          	
             $param=array("fromuser"=>$value['openid'],'acid'=>$acid,'uniacid'=>$uniacid);  
           	 add_money($param,$settings);
           }
      
	       
	       message("共有".$total."个老用户符合条件。".$count."个老用户发红包成功",referer(), 'success');
	       
   		 }
   		 
        include $this->template('qrcode');