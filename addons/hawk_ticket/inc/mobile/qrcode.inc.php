<?php
global $_W,$_GPC;
$this->checkMobile();
require_once(MODULE_ROOT.'/module/Order.class.php');
$id = $_GPC['id'];
if(empty($id)){
    message('票据错误',referfer,'error');
}
$order = new Order();
$ds = $order->getOne($id);
if(!$ds){
   message('票据错误',referfer,'error');
}
$url = $this->createMobileUrl('master',array('id'=>$id,'op'=>$_W['fans']['from_user']));
$url = substr($url,2);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/app/'.$url;
$surl = $url;
$url = base64_encode($url);

include $this->template('qrcode');