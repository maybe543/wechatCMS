<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Money.class.php';
require_once HK_ROOT . '/module/Father.class.php';
require_once HK_ROOT . '/module/Record.class.php';
require_once HK_ROOT . '/module/Fan.class.php';
require_once HK_ROOT . '/function/common.func.php';
$id = intval($_GPC['id']);
if(empty($id)){
    $this->error('访问错误');
}
$check = preparehandle($id);
if(is_error($check)){
    message($check['message']);
    exit();
}
$a = new Money();
$article = $a->getOne($id);
if(!$article){
    $this->error('访问错误');
}
//分享设置
$_share['title'] =  $article['title'];
$_share['imgUrl'] = tomedia($article['shareimg']);
$_share['desc'] = $article['description'];
$url = $this->createMobileUrl('article', array('id' => $id,'fp'=>$_W['fans']['from_user']));
$url = substr($url, 2);
$url = $_W['siteroot'] . 'app/' . $url;
$_share['link'] = $url;
//echo $url;
//用户关系处理
father_handle($_GPC['fp']);
//访问记录处理
record_handle($id);
//如果是跳转则直接跳转到地址
if($article['type']==1 && !empty($_GPC['fp']) ){

    header("Location: {$article['reurl']}");
}
include $this->template('article');
