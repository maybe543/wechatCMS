<?php

   global $_W;
   $fromuser=$_W['fans']['from_user'];
   $uniacid=$_W["uniacid"];
   $acid=$_W["acid"];
   require_once IA_ROOT . "/addons/water_jfhb/inc/common.php";   
   $param=array("fromuser"=>$fromuser,'acid'=>$acid,'uniacid'=>$uniacid);
   $jfhb_user=get_jfhbuser($param);
   $this->auth($jfhb_user);
   
   $settings=$this->module['config'];
   $tx_money=$settings['tx_money'];
   $jfsc_url=$settings['jfsc_url'];
   $stylepath='../addons/water_jfhb/template/style';

   $type=array('0'=> '关注','1'=>'邀请关注','2'=>'取消关注','3'=>'提现');
   $jfen=pdo_fetchall("select * from ".tablename('jfhb_user_log')." where uniacid={$_W['weid']} and openid='{$fromuser}'   order by createtime desc");
   $money=pdo_fetchcolumn("select money from ".tablename('jfhb_user')." where uniacid={$_W['weid']} and openid='{$fromuser}' ");
   
   include $this->template('jfen');
