<?php
 defined('IN_IA') or exit('Access Denied');
require_once(IA_ROOT . '/addons/quickdist/define.php');
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickDistModuleSite extends WeModuleSite{
    function __construct (){
        global $_W;
    }
    public function doWebMyCommission(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickdist', 'commission');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickcenter', 'fans');
        yload() -> func('quickcenter', 'global');
        $_commission = new Commission();
        $_fans = new Fans();
        $_order = new Order();
        $operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
        $from_user = $_GPC['from_user'];
        $fans = $_fans -> get($_W['weid'], $from_user);
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $list = $_memberorder -> getCommissionInfo($_W['weid'], $from_user);
        include $this -> template('mycommission');
    }
    public function doWebCommission(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickdist', 'commission');
        yload() -> classs('quickcenter', 'fans');
        yload() -> func('quickcenter', 'global');
        $_commission = new Commission();
        $_fans = new Fans();
        $operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
        if ($operation == 'display' || $operation == 'detail'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $com_key = 'order_leader';
            $fans_key = 'from_user';
            $order_fans_key = 'order_openid';
            if ($operation == 'display'){
                $conds = array();
            }else{
                $conds = array('from_user' => $_GPC['from_user']);
            }
            list($list, $total) = $_commission -> batchGet($_W['weid'], $conds, $pindex, $psize);
            $leaderfans = $_fans -> batchGetByOpenids($_W['weid'], my_array_fields($list, $com_key), $fans_key);
            $orderfans = $_fans -> batchGetByOpenids($_W['weid'], my_array_fields($list, $order_fans_key), $fans_key);
            $pager = pagination($total, $pindex, $psize);
            include $this -> template('commission');
        }else{
            message('unkonw op' . $operation, '', 'error');
        }
    }
    public function doWebOrder(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'wechatutil');
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'dispatch');
        yload() -> classs('quickshop', 'address');
        yload() -> classs('quickshop', 'express');
        yload() -> classs('quickdist', 'commission');
        yload() -> classs('quickdist', 'memberorder');
        yload() -> classs('quickcenter', 'fans');
        $_commission = new Commission();
        $_order = new Order();
        $_dispatch = new Dispatch();
        $_address = new Address();
        $_express = new Express();
        $_fans = new Fans();
        $_memberorder = new MemberOrder();
        $operation = !empty($_GPC['operation']) ? $_GPC['operation'] : 'display';
        if ($operation == 'display'){
            if (checksubmit('submit')){
                yload() -> classs('quickdist', 'memberorder');
                $_memberorder = new MemberOrder();
                $fail = 0;
                foreach ($_GPC['orderid'] as $id){
                    $item = $_order -> get($id);
                    if (empty($item) || ($item['status'] != Order :: $ORDER_RECEIVED)){
                        $fail++;
                        continue;
                    }
                    unset($item);
                    $_memberorder -> giveCommission($_W['weid'], $id);
                }
                if ($fail > 0){
                    message('订单批量佣金发放成功. 但有' . $fail . '个订单由于订单状态不符合要求，跳过发放，您可以确认无误后单独发放', referer(), 'success');
                }else{
                    message('订单批量佣金发放成功', referer(), 'success');
                }
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $status = !isset($_GPC['status']) ? Order :: $ORDER_RECEIVED : $_GPC['status'];
            $sendtype = !isset($_GPC['sendtype']) ? 1 : $_GPC['sendtype'];
            list($list, $total) = $_order -> batchGet($_W['weid'], array('status' => $status, 'sendtype' => $sendtype), null, $pindex, $psize);
            $pager = pagination($total, $pindex, $psize);
            if (!empty($list)){
                foreach ($list as & $row){
                    !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
                    $row['dispatch'] = $_dispatch -> get($row['dispatch']);
                    $com = $_memberorder -> calcCommission($_W['weid'], $row['id']);
                    foreach ($com as $c){
                        $ids[] = $c['from_user'];
                    }
                    $com_fans = $_fans -> batchGetByOpenids($_W['weid'], $ids, 'openid');
                    if (!empty($com)){
                        $row['commission_info'] = '<b>一级返利:</b>' . (empty($com_fans[$com[1]['from_user']]) ? '无上线' : $com_fans[$com[1]['from_user']]['nickname'] . sprintf('%.2f', $com[1]['com_val']) . '元. ') . ' <b>二级返利:</b>' . (empty($com_fans[$com[2]['from_user']]) ? '无上线' : $com_fans[$com[2]['from_user']]['nickname'] . sprintf('%.2f', $com[2]['com_val']) . '元. ') . ' <b>三级返利:</b>' . (empty($com_fans[$com[3]['from_user']]) ? '无上线' : $com_fans[$com[3]['from_user']]['nickname'] . sprintf('%.2f', $com[3]['com_val']) . '元. ');
                    }
                    unset($com_fans);
                    unset($ids);
                    unset($com);
                }
                unset($row);
            }
            if (!empty($addressids)){
                $address = $_address -> batchGetByIds($_W['weid'], $addressids, 'id');
            }
        }else if ($operation == 'settle'){
            yload() -> classs('quickdist', 'memberorder');
            $_memberorder = new MemberOrder();
            $id = intval($_GPC['id']);
            $item = $_order -> get($id);
            if (empty($item)){
                message("抱歉，订单不存在!", referer(), "error");
            }
            $_memberorder -> giveCommission($_W['weid'], $id);
            message('订单' . $item['ordersn'] . '佣金发放成功', referer(), 'success');
        }
        include $this -> template('order');
    }
    public function doWebNotify(){
        global $_W, $_GPC;
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickdist', 'distnotifier');
        $_distnotifier = new DistNotifier();
        $notify = $_distnotifier -> get($_W['weid']);
        if (checksubmit('submit')){
            if (empty($notify)){
                $ret = $_distnotifier -> create($_W['weid'], $_GPC);
            }else{
                $ret = $_distnotifier -> update($_W['weid'], $_GPC);
            }
            message('操作成功', referer(), 'success');
        }
        include $this -> template('notify');
    }
    private function getTemplateName(){
        return 'pink';
    }
    public function doWebEnableMenu(){
    }
    public function doMobileMember(){
    }
    public function getMemberCountByLevel1(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $followonly = intval($this -> module['config']['followonly']);
        return "<span  class='label label-warning'>" . $_member -> getMemberCountByLevel($_W['weid'], $_W['fans']['from_user'], 1, $followonly) . "人</span>";
    }
    public function getMemberCountByLevel2(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $followonly = intval($this -> module['config']['followonly']);
        return "<span  class='label label-warning'>" . $_member -> getMemberCountByLevel($_W['weid'], $_W['fans']['from_user'], 2, $followonly) . "人</span>";
    }
    public function getMemberCountByLevel3(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $followonly = intval($this -> module['config']['followonly']);
        return "<span  class='label label-warning'>" . $_member -> getMemberCountByLevel($_W['weid'], $_W['fans']['from_user'], 3, $followonly) . "人</span>";
    }
    private function genMemberInfoList($fans){
        $str = "";
        $followonly = intval($this -> module['config']['followonly']);
        if (!empty($fans)){
            foreach($fans as $item){
                $val = "<li><a href='javascript:void()'><img class='userDefinedAvatar' src='{$item['avatar']}' />{$item['nickname']}&nbsp;<em></em></a></li>";
                if (1 == $followonly){
                    if (1 == $item['follow']){
                        $str .= $val;
                    }
                }else{
                    $str .= $val;
                }
            }
        }else{
            $str .= "<li><a href='javascript:void()'><br><br><p style='text-align:center'>没有内容</p><br><br></a></li>";
        }
        return $str;
    }
    public function getMemberInfoByLevel1(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 1);
        return $this -> genMemberInfoList($mc);
    }
    public function getMemberInfoByLevel2(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 2);
        return $this -> genMemberInfoList($mc);
    }
    public function getMemberInfoByLevel3(){
        global $_W;
        yload() -> classs('quickdist', 'member');
        $_member = new member();
        $mc = $_member -> getMemberInfoByLevel($_W['weid'], $_W['fans']['from_user'], 3);
        return $this -> genMemberInfoList($mc);
    }
    public function getOrderCountByLevel1(){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $mc = $_memberorder -> getOrderCountByLevel($_W['weid'], $_W['fans']['from_user'], 1);
        return "<span  class='label label-warning'>" . $_memberorder -> getOrderCountByLevel($_W['weid'], $_W['fans']['from_user'], 1) . "单</span>";
    }
    public function getOrderCountByLevel2(){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        return "<span  class='label label-warning'>" . $_memberorder -> getOrderCountByLevel($_W['weid'], $_W['fans']['from_user'], 2) . "单</span>";
    }
    public function getOrderCountByLevel3(){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        return "<span  class='label label-warning'>" . $_memberorder -> getOrderCountByLevel($_W['weid'], $_W['fans']['from_user'], 3) . "单</span>";
    }
    private function genOrderInfoList($orders){
        global $_W;
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $str = "";
        if (!empty($orders)){
            foreach($orders as $item){
                if (empty($item['avatar'])){
                    $item['avatar'] = $_W['siteroot'] . 'addons/quickfans/images/default_head.png';
                }
                if (empty($item['nickname'])){
                    $item['nickname'] = '路人';
                }
                $str .= "<li><a href='javascript:void()'><img class='userDefinedAvatar' src='{$item['avatar']}' />{$item['nickname']}<em>" . $item['price'] . '元 ' . $_order -> getOrderStatusName($item['status']) . "</em></a></li>";
            }
        }else{
            $str .= "<li><a href='javascript:void()'><br><br><p style='text-align:center'>没有订单</p><br><br></a></li>";
        }
        return $str;
    }
    public function getOrderInfoByLevel1(){
        global $_W;
        $mc = $this -> getMyOrderInfoByLevel1($_W['fans']['from_user']);
        return $this -> genOrderInfoList($mc);
    }
    public function getOrderInfoByLevel2(){
        global $_W;
        $mc = $this -> getMyOrderInfoByLevel2($_W['fans']['from_user']);
        return $this -> genOrderInfoList($mc);
    }
    public function getOrderInfoByLevel3(){
        global $_W;
        $mc = $this -> getMyOrderInfoByLevel3($_W['fans']['from_user']);
        return $this -> genOrderInfoList($mc);
    }
    private function getMyOrderInfoByLevel1($from_user){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $mc = $_memberorder -> getOrderInfoByLevel($_W['weid'], $from_user, 1);
        return $mc;
    }
    private function getMyOrderInfoByLevel2($from_user){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $mc = $_memberorder -> getOrderInfoByLevel($_W['weid'], $from_user, 2);
        return $mc;
    }
    private function getMyOrderInfoByLevel3($from_user){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $mc = $_memberorder -> getOrderInfoByLevel($_W['weid'], $from_user, 3);
        return $mc;
    }
    private function getCommissionInfo(){
        global $_W;
        yload() -> classs('quickdist', 'memberorder');
        $_memberorder = new MemberOrder();
        $com_info = $_memberorder -> getCommissionInfo($_W['weid'], $_W['fans']['from_user']);
        return $com_info;
    }
    public function getTotalCommission(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_NEW or $c['status'] == Order :: $ORDER_PAYED or $c['status'] == Order :: $ORDER_DELIVERED or $c['status'] == Order :: $ORDER_RECEIVED or $c['status'] == Order :: $ORDER_CONFIRMED){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
    public function getTotalCommissionOrderNew(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_NEW){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
    public function getTotalCommissionOrderPayed(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_PAYED){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
    public function getTotalCommissionOrderDelivered(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_DELIVERED){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
    public function getTotalCommissionOrderReceived(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_RECEIVED){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
    public function getTotalCommissionOrderConfirmed(){
        $result = 0;
        $com_info = $this -> getCommissionInfo();
        foreach ($com_info as $c){
            if ($c['status'] == Order :: $ORDER_CONFIRMED){
                $result += $c['commission'];
            }
        }
        return "<span  class='label label-success'>" . number_format($result, 2) . "元</span>";
    }
}
