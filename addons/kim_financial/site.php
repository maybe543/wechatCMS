<?php
/**
 * 财务中心模块微站定义
 *
 * @author Kim 模块开发QQ:800083075
 * @url http://www.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
include_once 'common/common.inc.php';
class Kim_financialModuleSite extends WeModuleSite {

	public function doWebFinancialCenter() {
        global $_W,$_GPC;
		//这个操作被定义用来呈现 管理中心导航菜单
        if($_W['ispost'] && $_W['isajax']) {
            $setting = uni_setting($_W['uniacid'], array('groupdata'));
            $setting["groupdata"]["is_auto"] = $_GPC["is_auto"];
            pdo_update("uni_settings",array("groupdata"=>iserializer($setting["groupdata"])),array("uniacid"=>$_W['uniacid']));
            die(json_encode(array("code"=>1, "message"=>"操作成功.")));
        }
        $settings = get_settings();
        $service = explode("|",$settings["service_qqs"]);
        $qqs = array();
        foreach($service as $ser) {
            list($name,$qq) = explode("-",$ser);
            $qqs[] = array(
                "name"=>$name,
                "qq"=>$qq
            );
        }
        include $this->template('financial_center');
	}
    public function doWebChangePackage() {
        global $_W,$_GPC;
        $_W["user"]["packages"] = getUserGroupAccount();
        if (empty($_W['isfounder'])) {
            $group = pdo_fetch("SELECT * FROM ".tablename('users_group')." WHERE id = '{$_W['user']['groupid']}'");
            $group_packages = (array)@iunserializer($group['package']);
            $user_packages = (array)@iunserializer($_W['user']['package']);
            $group_account = uni_groups(array_merge($user_packages,$group_packages));
        } else {
            $group_account = uni_groups();
        }

        $allow_group = array_keys($group_account);
        $allow_group[] = 0;
        if(!empty($_W['isfounder'])) {
            $allow_group[] = -1;
        }

        if($_W['ispost']) {
            $uniacid = intval($_W['uniacid']);
            $groupid = intval($_GPC['groupid']);

            $state = uni_permission($_W['uid'], $uniacid);
            if($state != 'founder' && $state != 'manager') {
                exit('illegal-uniacid');
            }

            if(!in_array($groupid, $allow_group)) {
                exit('illegal-group');
            } else {
                pdo_update('uni_account', array('groupid' => $groupid), array('uniacid' => $uniacid));
                if($groupid == 0) {
                    exit('基础服务');
                } elseif($groupid == -1) {
                    exit('所有服务');
                } else {
                    exit($group_account[$groupid]['name']);
                }
            }
            exit();
        }
    }
    public function doWebGetPayResult() {
        global $_W,$_GPC;
        $order_no = $_GPC["order_no"];
        $order = pdo_fetch("SELECT * FROM ".tablename("uni_payorder")." WHERE orderid=:orderid", array(":orderid"=>$order_no));
        if(empty($order)){
            message("订单不存在!",$this->createWebUrl("FinancialCenter"));
        }
        if($order["status"] <> 1) {
            message("订单待支付状态，如果支付成功请与客服联系!",$this->createWebUrl("FinancialCenter"));
        }
        if($order["status"] == 1) {
            message("订单支付成功!",$this->createWebUrl("FinancialCenter"));
            exit;
        }
    }
}