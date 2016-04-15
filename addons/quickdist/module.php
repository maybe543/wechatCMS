<?php
 defined('IN_IA') or exit('Access Denied');
require_once(IA_ROOT . '/addons/quickdist/define.php');
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickDistModule extends WeModule{
    public function fieldsFormDisplay($rid = 0){
        global $_W;
        include $this -> template('rule');
    }
    public function fieldsFormSubmit($rid = 0){
        global $_GPC, $_W;
        if (!empty($_GPC['title'])){
            $data = array();
            $this -> saveSettings($data);
        }
        return true;
    }
    public function settingsDisplay($settings){
        global $_GPC, $_W;
        if (checksubmit('submit')){
            $cfg = array('followonly' => intval($_GPC['followonly']));
            if ($this -> saveSettings($cfg)){
                message('保存成功', 'refresh');
            }
        }
        $redirect_to = wurl('site/entry/center', array('m' => 'quickcenter', 'op' => 'display', 'weid' => $_W['weid']));
        if (checksubmit('installmenu')){
            $installed_menu = pdo_fetch('SELECT * FROM ' . tablename('quickcenter_module_bindings') . ' WHERE module=:module AND weid=:weid LIMIT 1', array(':module' => 'quickdist', ':weid' => $_W['weid']));
            if (empty($installed_menu)){
                $ret = pdo_query("INSERT INTO " . tablename('quickcenter_module_bindings') . " (`weid`, `groupid`, `identifier`, `pidentifier`, `displayorder`, `title`, `url`, `thumb`, `module`, `do`, `callback`, `rich_callback_enable`, `enable`) VALUES
              ({$_W['weid']}, 'group04', 'distmember', '', 0, '我的顾客', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberCountByLevel1', 0, 1),
            ({$_W['weid']}, 'group04', 'distmember2', '', 1, '我的间接顾客', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberCountByLevel2', 0, 0),
            ({$_W['weid']}, 'group04', 'distmember3', '', 3, '我的终极顾客', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberCountByLevel3', 0, 0),
            ({$_W['weid']}, 'group04', 'distmember2-detail', 'distmember2', 5, '我的间接顾客详情', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberInfoByLevel2', 1, 0),
            ({$_W['weid']}, 'group04', 'distmember-detail', 'distmember', 4, '我的顾客详情', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberInfoByLevel1', 1, 1),
            ({$_W['weid']}, 'group04', 'distmember3-detail', 'distmember3', 6, '我的终极顾客详情', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getMemberInfoByLevel3', 1, 0),
            ({$_W['weid']}, 'group04', 'mypromotionorder1', '', 1, '顾客消费记录', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getOrderCountByLevel1', 0, 1),
            ({$_W['weid']}, 'group05', 'mypromotionorder2', '', 2, '间接顾客下单', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getOrderCountByLevel2', 0, 0),
            ({$_W['weid']}, 'group05', 'mypromotionorder3', '', 3, '终极顾客下单', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getOrderCountByLevel3', 0, 0),
            ({$_W['weid']}, 'group05', 'mypromotionorder3-detail', 'mypromotionorder3', 3, '终极顾客消费详情', '', '', 'quickdist', 'Center', 'getOrderInfoByLevel3', 1, 0),
            ({$_W['weid']}, 'group09', 'commission', '', -1, '利润统计', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommission', 0, 1),
            ({$_W['weid']}, 'group05', 'mypromotionorder2-detail', 'mypromotionorder2', 2, '间接顾客消费详情', '', '', 'quickdist', 'Center', 'getOrderInfoByLevel2', 1, 0),
            ({$_W['weid']}, 'group09', 'commission-new', 'commission', 1, '待支付订单预计利润', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommissionOrderNew', 0, 1),
            ({$_W['weid']}, 'group04', 'mypromotionorder1-detail', 'mypromotionorder1', 1, '顾客消费详情', '', '', 'quickdist', 'Center', 'getOrderInfoByLevel1', 1, 1),
            ({$_W['weid']}, 'group09', 'commission-payed', 'commission', 2, '已支付订单预计利润', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommissionOrderPayed', 0, 1),
            ({$_W['weid']}, 'group09', 'commission-delivered', 'commission', 3, '已发货订单预计利润', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommissionOrderDelivered', 0, 1),
            ({$_W['weid']}, 'group09', 'commission-received', 'commission', 4, '已收货订单预计利润', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommissionOrderReceived', 0, 1),
            ({$_W['weid']}, 'group09', 'commission-confirmed', 'commission', 5, '已转入钱包利润', '', '{$_W['siteroot']}/addons/quickdist/images/icon.png', 'quickdist', 'Center', 'getTotalCommissionOrderConfirmed', 0, 1)");
                if (!empty($ret)){
                    message('安装菜单成功', $redirect_to, 'success');
                }else{
                    message('安装菜单失败, 请重试，或联系管理员', referer(), 'error');
                }
            }else{
                message('菜单已经存在, 无需再次安装。如果需要重新安装，请先点击按钮卸载菜单。', referer(), 'error');
            }
        }
        if (checksubmit('uninstallmenu')){
            $ret = pdo_query("DELETE FROM " . tablename('quickcenter_module_bindings') . " WHERE weid=:weid AND module=:module", array(':weid' => $_W['weid'], ':module' => 'quickdist'));
            message('卸载成功', $redirect_to, 'success');
        }
        yload() -> classs('quickcenter', 'FormTpl');
        include $this -> template('setting');
    }
}
