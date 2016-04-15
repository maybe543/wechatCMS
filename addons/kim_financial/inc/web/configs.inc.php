<?php
/**
 * 会员财务中心
 *
 * 作者:Kim
 * 模块定制QQ: 800083075
 * 后台体验地址: http://www.012wz.com
 */
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;
if(!$_W['isfounder']) {
    message('不能访问, 需要创始人权限才能访问.');
}
$item = get_settings();
if($_W["ispost"] && checksubmit()) {
    $save_db["value"] = @iserializer($_GPC["save"]);
    if(empty($item)) {
        $save_db["key"] = "kim_financial";
        pdo_insert("core_settings",$save_db);
    }else{
        pdo_update("core_settings",$save_db, array("key"=>"kim_financial"));
    }
    $packages = $_GPC["packages"];
    foreach($packages as $k=>$v){
        pdo_update("uni_group",array("price"=>$v["price"],"hide"=>$v["hide"]),array("id"=>$k));
    }
    $groups = $_GPC["groups"];
    foreach($groups as $k=>$v){
        pdo_update("users_group",array("discount"=>$v["discount"]),array("id"=>$k));
    }
    message("操作成功",$this->createWebUrl("Configs"));
}
if(!empty($item)){
    $save = $item;
}elseif(empty($item) && !empty($_GPC["save"])) {
    $save = $_GPC["save"];
}else{
    $save = array(
        "dx_UnitPrice"=>20,
        "tx_date"=>7
    );
}
$groups = pdo_fetchall("SELECT id, name, discount FROM ".tablename('users_group')." ORDER BY id ASC");
include $this->template('financial_configs');