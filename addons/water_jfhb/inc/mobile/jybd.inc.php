<?php

   global $_W, $_GPC;
   
   $uniacid=$_W["uniacid"];
   $acid=$_W["acid"];
   $fromuser=$_W['fans']['from_user'];
   require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
   $settings=$this->module['config'];   
   $param=array("fromuser"=>$fromuser,'acid'=>$acid,'uniacid'=>$uniacid);
   $ret=get_jfhbuser($param);
   if ($ret['code']!=0){
     message($ret['msg']);
   }
   $jfhb_user=$ret['user'];
   
 
   $this->auth($jfhb_user);
     
   define('APPID', $settings['appid']);
   define('SECRET', $settings['secret']);

   $jyopenid=getFromUser($settings);
   

     
   
   if (!empty($jfhb_user['jyopenid']) && $jfhb_user['jyopenid']==$fromuser){
   	    $message="已经绑定成功";
   	    include $this->template('jybd');
   	    return;
   	}
    
       
    if (!empty($jfhb_user['jyopenid']) && $jfhb_user['jyopenid']!=$fromuser){
   	  $message="此账号已经被别人绑定";
   	  include $this->template('jybd');
   	  return;
    }

    $temp=pdo_update("jfhb_user",
             array(
             	   "jyopenid"=>$jyopenid,            	  
             ),array("id"=>$jfhb_user['id'])); 
    
   if ($temp==false){
   	$message="更新失败";
   } else {
   		$message="绑定提现账号成功";
   }
   
 
   
   include $this->template('jybd');