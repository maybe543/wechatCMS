<?php

   global $_W;
   $this->auth();
   $settings=$this->module['config'];
   $tx_money=$settings['tx_money'];
   $stylepath='../addons/water_jfhb/template/style';
   $fromuser=$_W['fans']['from_user'];
   
   $jfhb_user=pdo_fetchall("select * from ".tablename('jfhb_user')." where parent_openid='{$fromuser}' and openid!='{$fromuser}' and uniacid={$_W['weid']}");
   
   include $this->template('my_underline');