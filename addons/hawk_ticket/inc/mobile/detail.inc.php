<?php
global $_W,$_GPC;
$this->checkMobile();
if(empty($_GPC['id'])){
    message("访问错误",referer,'warning');
}
$id = intval($_GPC['id']);
require_once(MODULE_ROOT.'/module/Activity.class.php');
$act = new Activity();
$ds = $act->getOne($id);
if(!$ds){
    message("访问错误",referer,'warning');
}
//分享设置
$_share['title'] =  $ds['title'];
if(!empty($ds['shareimg'])){
    $_share['imgUrl'] = tomedia($ds['shareimg']);
}
$_share['desc'] = $ds['description'];
$url = $this->createMobileUrl('detail', array('id' => $id));
$url = substr($url, 2);
$url = $_W['siteroot'] . 'app/' . $url;
$_share['link'] = $url;
$ds['remain']= $ds['tlimit'] - $ds['used'];
$ds['singleimg'] = tomedia($ds['singleimg']);
include $this->template('detail');