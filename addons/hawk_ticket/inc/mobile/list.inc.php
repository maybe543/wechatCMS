<?php
global $_W,$_GPC;
$this->checkMobile();
require_once(MODULE_ROOT.'/module/Activity.class.php');
require_once(MODULE_ROOT.'/module/Order.class.php');
$act = new Activity();
$order = new Order();
$filters = array();
$filters['status']= 0;

$pindex = intval($_GPC['page']);
$pindex = max($pindex, 1);
$psize = 20;
$total = 0;
$ds = $act->getAll($filters,$pindex, $psize, $total);
$pager = pagination($total, $pindex, $psize);
if(is_array($ds)){
    foreach($ds as $k=>&$v){
        $orders = $order->getOrders($v['id']);
        if(!$orders){
            $used = 0;
        }else{
            $used = count($orders);
        }
        $v['remain'] = $v['tlimit'] - $used;
    }
}
include $this->template('list');