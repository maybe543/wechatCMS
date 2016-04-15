<?php
   global $_W, $_GPC;
   $fromuser=$_W['fans']['from_user'];
   $uniacid=$_W["uniacid"];
   $acid=$_W["acid"];
   require_once IA_ROOT . "/addons/water_jfhb/inc/common.php"; 
   $param=array("fromuser"=>$fromuser,'acid'=>$acid,'uniacid'=>$uniacid);
   $ret=get_jfhbuser($param);
   if ($ret['code']!=0){
     message($ret['msg']);
   }
   $jfhb_user=$ret['user'];
    
   $this->auth($jfhb_user);
   

   $stylepath='../addons/water_jfhb/template/style';
   $pindex = max(1, intval($_GPC['page']));	
   $psize= 20;
  $uniacid=$_W["uniacid"];
  $list= pdo_fetchall("SELECT headimgurl,nickname,tx_money  from ".tablename('jfhb_user')."  where uniacid=$uniacid   order by tx_money desc LIMIT ". ($pindex -1) * $psize . ',' .$psize);
  include $this->template('top');