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
checklogin();
if($_W['ispost'] && $_W['isajax']) {
    $user = pdo_fetch("SELECT U.* FROM ".tablename("uni_account_users")." AS A LEFT JOIN ".tablename("users")." AS U ON A.uid=U.uid WHERE A.uniacid=:uniacid AND A.role='manager'",array(":uniacid"=>$_W['uniacid']));
    if(empty($user)) die(json_encode(array("code"=>1, "message"=>"扣费帐号不存在.")));
    $res = buy_package($user, $_GPC['pid'], $_GPC['total']);
    if(!is_error($res)){
        die(json_encode(array("code"=>1, "message"=>"购买成功.")));
    }
    die(json_encode(array("code"=>0, "message"=>$res["message"])));
}

$idList = pdo_fetchall("SELECT id FROM ".tablename("uni_group"));
$_items = array();
foreach($idList as $item){
    $_items[] = $item["id"];
}
$list = uni_groups($_items);
$curr_count = count($list[$_W["user"]["account"]["groupid"]]['modules']);
include $this->template('financial_buypackage');