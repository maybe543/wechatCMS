<?php
global $_W,$_GPC;
$this->checkMobile();
$input = array();
if(empty($_GPC['actid'])){
    message('订单错误',referer,'warning');
}
$id = $_GPC['actid'];
require_once(MODULE_ROOT.'/module/Activity.class.php');
require_once(MODULE_ROOT.'/module/Order.class.php');
$act = new Activity();
$ds = $act->getOne($id);
if(!$ds){
    message("访问错误",referer,'warning');
}
//写入订单
$input = array();
$input['actid'] = $id;
$input['fee'] = $ds['fee'];
$order = new Order();
$res = $order->create($input);
if($res){
    $orderid = $res;
    $params = array();
    $params['id'] = $orderid;
    $params['fee'] = $ds['fee'];
    $params['title'] = $ds['proname'];

    $params = base64_encode(serialize($params));
    $url = $this->createMobileUrl('pay',array('pars'=>$params));
    header("Location:{$url}");
    exit();
}else{
    message('订单出错',referfer,'error');
}
