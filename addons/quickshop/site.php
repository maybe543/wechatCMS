<?php
 defined('IN_IA') or exit('Access Denied');
session_start();
include 'define.php';
require_once(IA_ROOT . '/addons/quickcenter/loader.php');
class QuickShopModuleSite extends WeModuleSite{
    function __construct (){
        global $_W;
    }
    public function doWebAchive(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $today_str = date('Y-m-d', TIMESTAMP) . ' 00:00:00';
        $today_ts = strtotime($today_str);
        $eclipse = TIMESTAMP - $today_ts;
        $new_status = array(Order :: $ORDER_NEW);
        $deliever_status = array(Order :: $ORDER_PAYED);
        $effective_status = array(Order :: $ORDER_PAYED, Order :: $ORDER_DELIVERED, Order :: $ORDER_RECEIVED, Order :: $ORDER_CONFIRMED);
        $ToPay1 = $_order -> getAchievementByTime($_W['weid'], $new_status, $eclipse);
        $ToDeliever1 = $_order -> getAchievementByTime($_W['weid'], $deliever_status, $eclipse);
        $Pay1 = $_order -> getAchievementByTime($_W['weid'], $effective_status, $eclipse);
        $Pay2 = $_order -> getAchievementByTime($_W['weid'], $effective_status, 60 * 60 * 24 * 1 + $eclipse);
        $Pay7 = $_order -> getAchievementByTime($_W['weid'], $effective_status, 60 * 60 * 24 * 6 + $eclipse);
        $Pay30 = $_order -> getAchievementByTime($_W['weid'], $effective_status, 60 * 60 * 24 * 29 + $eclipse);
        yload() -> classs('quickshop', 'shopstat');
        $_shopstat = new ShopStat();
        $viewcounts = $_shopstat -> getAllGoodsViewCount($_W['weid']);
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $UserDay1 = $_fans -> getActiveUserByTime($_W['weid'], 1, $eclipse);
        $UserDay2 = $_fans -> getActiveUserByTime($_W['weid'], 1, 60 * 60 * 24 * 1 + $eclipse);
        $UserDay3 = $_fans -> getActiveUserByTime($_W['weid'], 1, 60 * 60 * 24 * 2 + $eclipse);
        $UserDay7 = $_fans -> getActiveUserByTime($_W['weid'], 1, 60 * 60 * 24 * 6 + $eclipse);
        $UserDay30 = $_fans -> getActiveUserByTime($_W['weid'], 1, 60 * 60 * 24 * 29 + $eclipse);
        $UserFallDay1 = $_fans -> getActiveUserByTime($_W['weid'], 0, $eclipse);
        $UserFallDay2 = $_fans -> getActiveUserByTime($_W['weid'], 0, 60 * 60 * 24 * 1 + $eclipse);
        $UserFallDay3 = $_fans -> getActiveUserByTime($_W['weid'], 0, 60 * 60 * 24 * 2 + $eclipse);
        $UserFallDay7 = $_fans -> getActiveUserByTime($_W['weid'], 0, 60 * 60 * 24 * 6 + $eclipse);
        $UserFallDay30 = $_fans -> getActiveUserByTime($_W['weid'], 0, 60 * 60 * 24 * 29 + $eclipse);
        include $this -> template('achievement');
    }
    public function doWebCategory(){
        global $_GPC, $_W;
        $this -> doWebAuth();
        yload() -> classs('quickshop', 'category');
        yload() -> classs('quickcenter', 'FormTpl');
        $_category = new Category();
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display'){
            if (!empty($_GPC['displayorder'])){
                foreach ($_GPC['displayorder'] as $id => $displayorder){
                    $_category -> update($_W['weid'], $id, array('displayorder' => $displayorder));
                }
                message('分类排序更新成功！', $this -> createWebUrl('category', array('op' => 'display')), 'success');
            }
            $children = array();
            $category = $_category -> batchGet($_W['weid']);
            foreach ($category as $index => $row){
                if (!empty($row['parentid'])){
                    $children[$row['parentid']][] = $row;
                    unset($category[$index]);
                }
            }
            include $this -> template('category');
        }elseif ($operation == 'post'){
            $parentid = intval($_GPC['parentid']);
            $id = intval($_GPC['id']);
            if (!empty($id)){
                $category = $_category -> get($id);
            }else{
                $category = array('displayorder' => 0,);
            }
            if (!empty($parentid)){
                $parent = $_category -> get($parentid);
                if (empty($parent)){
                    message('抱歉，上级分类不存在或是已经被删除！', $this -> createWebUrl('post'), 'error');
                }
            }
            if (checksubmit('submit')){
                if (empty($_GPC['catename'])){
                    message('抱歉，请输入分类名称！');
                }
                $data = array('weid' => $_W['weid'], 'name' => $_GPC['catename'], 'enabled' => intval($_GPC['enabled']), 'displayorder' => intval($_GPC['displayorder']), 'isrecommend' => intval($_GPC['isrecommend']), 'description' => $_GPC['description'], 'parentid' => intval($parentid), 'thumb' => $_GPC['thumb'],);
                if (!empty($id)){
                    unset($data['parentid']);
                    $_category -> update($_W['weid'], $id, $data);
                }else{
                    $id = $_category -> create($data);
                }
                message('更新分类成功！', $this -> createWebUrl('category', array('op' => 'display')), 'success');
            }
            include $this -> template('category');
        }elseif ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $category = $_category -> get($id);
            if (empty($category)){
                message('抱歉，分类不存在或是已经被删除！', $this -> createWebUrl('category', array('op' => 'display')), 'error');
            }
            $_category -> remove($_W['weid'], $id);
            message('分类删除成功！', $this -> createWebUrl('category', array('op' => 'display')), 'success');
        }
    }
    public function doWebSetGoodsProperty(){
        global $_GPC, $_W;
        $this -> doWebAuth();
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);
        empty($data) ? ($data = 1) : $data = 0;
        if (!in_array($type, array('new', 'hot', 'recommend', 'discount'))){
            die(json_encode(array("result" => 0)));
        }
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        $_goods -> update($_W['weid'], $id, array("is" . $type => $data));
        die(json_encode(array("result" => 1, "data" => $data)));
    }
    public function doWebGoods(){
        global $_GPC, $_W;
        $this -> doWebAuth();
        yload() -> classs('quickcenter', 'FormTpl');
        load() -> func('tpl');
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'category');
        yload() -> classs('quickshop', 'dispatch');
        $_goods = new Goods();
        $_category = new Category();
        $_dispatch = new Dispatch();
        $category = $_category -> batchGet($_W['weid'], array(), 'id');
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'fork'){
            $id = intval($_GPC['id']);
            $cid = $_goods -> fork($id);
            if (!empty($cid)){
                message('添加子规格商品成功，前往编辑', $this -> createWebUrl('goods', array('id' => $cid, 'op' => 'post')), 'success');
            }else{
                message('添加子规格失败。无效的父类商品ID', referer(), 'error');
            }
        }else if ($operation == 'post'){
            $id = intval($_GPC['id']);
            if (!empty($id)){
                $item = $_goods -> get($id);
                if (empty($item)){
                    message('抱歉，商品不存在或是已经删除！', '', 'error');
                }
                $piclist = unserialize($item['thumb_url']);
            }
            if (empty($category)){
                message('抱歉，请您先添加商品分类！', $this -> createWebUrl('category', array('op' => 'post')), 'error');
            }
            if (checksubmit('submit')){
                if (empty($_GPC['goodsname'])){
                    message('请输入商品名称！');
                }
                if (empty($_GPC['pcate'])){
                    message('请选择商品分类！');
                }
                if (empty($_GPC['totalcnf'])){
                    message('请选择减库存的方式。秒杀商品建议使用拍下减库存，一般商品建议使用付款减库存！');
                }
                if (floatval($_GPC['max_coupon_credit']) >= floatval($_GPC['marketprice'])){
                    message("积分抵扣额{$_GPC['max_coupon_credit']}不得高于商品售价{$_GPC['marketprice']}！");
                }
                if (!empty($_GPC['pgoodsid'])){
                    if ($_GPC['pgoodsid'] == $_GPC['id']){
                        message('自己不能是自己的主规格商品', '', 'error');
                    }
                    $g = $_goods -> get($_GPC['pgoodsid']);
                    if (!empty($g['pgoodsid'])){
                        message($g['title'] . '是子规格商品，不能作为' . $_GPC['title'] . '的主规格商品', '', 'error');
                    }
                    unset($g);
                    $g = $_goods -> batchGetSubSpec($_W['weid'], $_GPC['id']);
                    if (!empty($g)){
                        message($_GPC['title'] . '是主规格商品, 不能变更为子规格商品', '', 'error');
                    }
                }
                $data = array('weid' => intval($_W['weid']), 'displayorder' => intval($_GPC['displayorder']), 'title' => $_GPC['goodsname'], 'pcate' => intval($_GPC['pcate']), 'ccate' => intval($_GPC['ccate']), 'support_delivery' => intval($_GPC['support_delivery']), 'goodstype' => intval($_GPC['goodstype']), 'sendtype' => intval($_GPC['sendtype']), 'credittype' => intval($_GPC['credittype']), 'isrecommend' => intval($_GPC['isrecommend']), 'ishot' => intval($_GPC['ishot']), 'isnew' => intval($_GPC['isnew']), 'isdiscount' => intval($_GPC['isdiscount']), 'istime' => intval($_GPC['istime']), 'timestart' => strtotime($_GPC['timestart']), 'timeend' => strtotime($_GPC['timeend']), 'isminimode' => intval($_GPC['isminimode']), 'description' => $_GPC['description'], 'content' => htmlspecialchars_decode($_GPC['content']), 'cover_content' => htmlspecialchars_decode($_GPC['cover_content']), 'secret_content' => htmlspecialchars_decode($_GPC['secret_content']), 'spec' => htmlspecialchars_decode($_GPC['spec']), 'goodssn' => $_GPC['goodssn'], 'unit' => $_GPC['unit'], 'createtime' => TIMESTAMP, 'total' => intval($_GPC['total']), 'totalcnf' => intval($_GPC['totalcnf']), 'marketprice' => $_GPC['marketprice'], 'weight' => $_GPC['weight'], 'costprice' => $_GPC['costprice'], 'productprice' => $_GPC['productprice'], 'productsn' => $_GPC['productsn'], 'credit' => intval($_GPC['credit']), 'credit2' => floatval($_GPC['credit2']), 'maxbuy' => intval($_GPC['maxbuy']), 'hasoption' => intval($_GPC['hasoption']), 'sales' => intval($_GPC['sales']), 'status' => intval($_GPC['status']), 'timelinetitle' => $_GPC['timelinetitle'], 'timelinedesc' => $_GPC['timelinedesc'], 'killdiscount' => $_GPC['killdiscount'], 'killmindiscount' => $_GPC['killmindiscount'], 'killtotaldiscount' => $_GPC['killtotaldiscount'], 'killmaxtime' => intval($_GPC['killmaxtime']), 'killenable' => intval($_GPC['killenable']), 'min_visible_level' => intval($_GPC['min_visible_level']), 'min_buy_level' => intval($_GPC['min_buy_level']), 'rate1' => floatval($_GPC['rate1']), 'rate2' => floatval($_GPC['rate2']), 'rate3' => floatval($_GPC['rate3']), 'max_coupon_credit' => floatval($_GPC['max_coupon_credit']), 'dealeropenid' => $_GPC['dealeropenid'], 'thumb' => $_GPC['thumb'], 'timelinethumb' => $_GPC['timelinethumb'], 'thumb_url' => serialize($_GPC['thumb_url']),);
                if (empty($id)){
                    $id = $_goods -> create($data);
                }else{
                    unset($data['createtime']);
                    $_goods -> update($_W['weid'], $id, $data);
                }
                message('商品更新成功！', $this -> createWebUrl('goods', array('op' => 'post', 'id' => $id)), 'success');
            }
            if (!empty($item['id']) and empty($item['pgoodsid'])){
                list($subspecs, $total) = $_goods -> batchGetSubSpec($_W['weid'], $item['id']);
            }
            if (!empty($item['pgoodsid'])){
                $parent = $_goods -> get($item['pgoodsid']);
            }
            $dispatch = $_dispatch -> getUnique($_W['weid']);
        }elseif ($operation == 'display'){
            if (!empty($_GPC['displayorder'])){
                foreach ($_GPC['displayorder'] as $id => $displayorder){
                    $_goods -> update($_W['weid'], $id, array('displayorder' => $displayorder));
                }
                message('分类排序更新成功！', $this -> createWebUrl('goods', array('op' => 'display')), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 40;
            list($list, $total) = $_goods -> batchGet($_W['weid'], $_GPC, $pindex, $psize);
            $pager = pagination($total, $pindex, $psize);
        }elseif ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $row = $_goods -> get($id);
            if (empty($row)){
                message('抱歉，商品不存在或是已经被删除！');
            }
            $_goods -> markDelete($_W['weid'], $id);
            message('删除成功！', referer(), 'success');
        }
        include $this -> template('goods');
    }
    public function doWebOrder(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'dispatch');
        yload() -> classs('quickshop', 'address');
        yload() -> classs('quickshop', 'express');
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickcenter', 'wechatutil');
        $_order = new Order();
        $_dispatch = new Dispatch();
        $_address = new Address();
        $_express = new Express();
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display'){
            if (checksubmit('batchconfirmreceived')){
                yload() -> classs('quickcenter', 'fans');
                $_fans = new Fans();
                $skip = 0;
                foreach ($_GPC['orderid'] as $id){
                    $order = $_order -> get($id);
                    if (empty($order)){
                        continue;
                    }
                    if (TIMESTAMP - $order['updatetime'] < 1 * 60 * 60 * 24 && Order :: $ORDER_DELIVERED == $order['status']){
                        $skip++;
                        continue;
                    }
                    $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_RECEIVED));
                    $this -> notifyUser($_W['weid'], $id, 'notifyAdminConfirmed');
                    if (Order :: $PAY_DELIVERY == $order['paytype']){
                        $_fans -> setVIP($order['weid'], $order['from_user']);
                    }
                }
                message('订单批量确认收货操作成功！' . ($skip == 0?'':'其中有' . $skip . '个订单由于未满1天自动跳过'), referer(), 'success');
            }else if (checksubmit('batchremoveorder')){
                foreach ($_GPC['orderid'] as $id){
                    $order = $_order -> get($id);
                    if (empty($order)){
                        continue;
                    }
                    if ($order['status'] == Order :: $ORDER_NEW or $order['status'] == Order :: $ORDER_CANCEL){
                        $_order -> remove($_W['weid'], $id);
                    }
                }
                message('批量删除订单操作成功！', $this -> createWebUrl('Order', array('status' => Order :: $ORDER_NEW)), 'success');
            }
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20;
            $status = !isset($_GPC['status']) ? 3 : $_GPC['status'];
            $conds = array('status' => $status);
            if (isset($_GPC['search']) && !empty($_GPC['search'])){
                yload() -> classs('quickshop', 'ordersearch');
                $_search = new OrderSearch();
                $conds[$_GPC['searchtype']] = trim($_GPC['search']);
                $pindex = 1;
                $psize = 100000;
                list($list, $total) = $_search -> search($_W['weid'], $conds, null, $pindex, $psize);
                $pager = pagination($total, $pindex, $psize);
            }else{
                list($list, $total) = $_order -> batchGet($_W['weid'], $conds, null, $pindex, $psize);
                $pager = pagination($total, $pindex, $psize);
            }
            if (!empty($list)){
                foreach ($list as & $row){
                    !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
                    $row['dispatch'] = $_dispatch -> get($row['dispatch']);
                }
                unset($row);
            }
            if (!empty($addressids)){
                $address = $_address -> batchGetByIds($_W['weid'], $addressids, 'id');
            }
            $status_text = $_order -> getOrderStatusName($status);
        }elseif ($operation == 'detail'){
            $id = intval($_GPC['id']);
            $item = $_order -> get($id);
            if (empty($item)){
                message("抱歉，订单不存在!", referer(), "error");
            }
            if (checksubmit('confirmsend')){
                if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])){
                    message('请输入快递单号！');
                }
                if (!empty($item['transid'])){
                }
                if ($item['sendtype'] == Dispatch :: $EXPRESS){
                    $order_next_status = Order :: $ORDER_DELIVERED;
                }else{
                    $order_next_status = Order :: $ORDER_RECEIVED;
                }
                $data = array('status' => $order_next_status, 'remark' => $_GPC['remark'], 'express' => $_GPC['express'], 'expresscom' => $_GPC['expresscom'], 'expresssn' => $_GPC['expresssn'],);
                $_order -> update($_W['weid'], $id, $data);
                $this -> notifyUser($_W['weid'], $id, 'notifyDelivered');
                message('发货操作成功！', referer(), 'success');
            }
            if (checksubmit('addremark')){
                $data = array('remark' => $_GPC['remark'],);
                $_order -> update($_W['weid'], $id, $data);
                message('更新备注成功！', referer(), 'success');
            }
            if (checksubmit('cancelsend')){
                if (!empty($item['transid'])){
                }
                $data = array('status' => Order :: $ORDER_PAYED, 'remark' => $_GPC['remark'],);
                $_order -> update($_W['weid'], $id, $data);
                message('取消发货操作成功！', referer(), 'success');
            }
            if (checksubmit('finish')){
                $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_RECEIVED, 'remark' => $_GPC['remark']));
                $this -> notifyUser($_W['weid'], $id, 'notifyAdminConfirmed');
                if (Order :: $PAY_DELIVERY == $item['paytype']){
                    yload() -> classs('quickcenter', 'fans');
                    $_fans = new Fans();
                    $_fans -> setVIP($item['weid'], $item['from_user']);
                    message('订单操作成功！用户VIP等级提升成功。', referer(), 'success');
                }else{
                    message('订单操作成功！', referer(), 'success');
                }
            }
            if (checksubmit('cancelpay')){
                $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_NEW, 'remark' => $_GPC['remark']));
                $this -> setOrderStock($id, false);
                $this -> setOrderCredit($id, false);
                message('取消订单付款操作成功！', referer(), 'success');
            }
            if (checksubmit('remove')){
                $order = $_order -> get($id);
                if (empty($order)){
                    message('订单已经删除，无需重复删除！', referer(), 'error');
                }
                if ($order['status'] == Order :: $ORDER_NEW or $order['status'] == Order :: $ORDER_CANCEL){
                    $_order -> remove($_W['weid'], $id);
                    message('删除订单操作成功！', $this -> createWebUrl('Order', array('status' => Order :: $ORDER_NEW)), 'success');
                }else{
                    message('该状态下订单不允许删除！', referer(), 'error');
                }
            }
            if (checksubmit('confirmpay')){
                $order = $_order -> get($id);
                if ($order['status'] == Order :: $ORDER_NEW){
                    $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_PAYED, 'paytype' => Order :: $PAY_ONLINE, 'remark' => $_GPC['remark']));
                    $transid = '';
                    $this -> onOrderPayedSuccess($_order, $order['weid'], $order['from_user'], $id, $transid, Order :: $PAY_ONLINE);
                    message('确认订单付款操作成功！', referer(), 'success');
                }else{
                    message('订单状态不是待支付，无法支付！', referer(), 'error');
                }
            }
            if (checksubmit('close')){
                $item = $_order -> get($id);
                if (!empty($item['transid'])){
                }
                $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_CANCEL, 'remark' => $_GPC['remark']));
                message('订单关闭操作成功！', referer(), 'success');
            }
            if (checksubmit('open')){
                $_order -> update($_W['weid'], $id, array('status' => Order :: $ORDER_NEW, 'remark' => $_GPC['remark']));
                message('开启订单操作成功！', referer(), 'success');
            }
            if (checksubmit('changeprice')){
                $_order -> update($_W['weid'], $id, array('price' => floatval($_GPC['newprice'])));
                message('改价成功！', referer(), 'success');
            }
            if (checksubmit('talktouser')){
                $msg = $_GPC['talktouser_msg'];
                $openid = $_GPC['openid'];
                yload() -> classs('quickcenter', 'custommsg');
                if (empty($msg)){
                    message('请输入消息内容！', referer(), 'error');
                }else if (empty($openid)){
                    message('请指定接收消息的用户的OpenID！', referer(), 'error');
                }
                $_custommsg = new CustomMsg();
                $ret = $_custommsg -> sendText($_W['weid'], $openid, $msg);
                message('发送消息成功！', referer(), 'success');
            }
            $dispatch = $_dispatch -> get($item['dispatch']);
            if (!empty($dispatch) && !empty($dispatch['express'])){
                $express = $_express -> get($dispatch['express']);
            }
            $item['user'] = $_address -> get($item['addressid']);
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            $fans = $_fans -> get($_W['weid'], $item['from_user']);
            $goods = $_order -> getDetailedGoods($id);
            $item['goods'] = $goods;
            yload() -> classs('quickdist', 'memberorder');
            $_memberorder = new MemberOrder();
            $commission = $_memberorder -> calcCommission($_W['weid'], $id);
            foreach ($commission as $c){
                $ids[] = $c['from_user'];
            }
            $com_fans = $_fans -> batchGetByOpenids($_W['weid'], $ids, 'openid');
        }
        include $this -> template('order');
    }
    private function setOrderStock($id = '', $minus = true){
        global $_W;
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'order');
        $_goods = new Goods();
        $_order = new Order();
        $goods = $_order -> getDetailedGoods($id);
        foreach ($goods as $item){
            if ($item['totalcnf'] != 3){
                $this -> setGoodsStock($item['id'], $item['totalcnf'], $item['goodstotal'], $item['total'], $item['sales'], $minus);
            }
        }
    }
    private function setGoodsStock($id, $totalcnf, $stock, $buyamount, $sold, $minus = true){
        global $_W;
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        $data = array();
        if ($minus){
            if ($totalcnf != 2){
                if (!empty($stock) && $stock != -1){
                    $data['total'] = $stock - $buyamount;
                    if ($data['total'] == -1){
                        $data['total'] == 0;
                    }
                }
            }
            $data['sales'] = $sold + $buyamount;
        }else{
            if ($totalcnf != 2){
                if (!empty($stock) && $stock != -1){
                    $data['total'] = $stock + $buyamount;
                    if ($data['total'] == -1){
                        $data['total'] == 0;
                    }
                }
            }
            $data['sales'] = $sold - $buyamount;
        }
        $_goods -> update($_W['weid'], $id, $data);
    }
    public function getCartTotal(){
        global $_W;
        yload() -> classs('quickshop', 'cart');
        $_cart = new Cart();
        $cartotal = $_cart -> total($_W['weid'], $_W['fans']['from_user']);
        return empty($cartotal) ? 0 : $cartotal;
    }
    private function tryLink(){
        global $_GPC, $_W, $_COOKIE;
        yload() -> classs('quicklink', 'translink');
        $_link = new TransLink();
        $_link -> preLink($_W['weid'], $_GPC['shareby']);
        WeUtility :: logging("shareby", array('GPC' => $_GPC['shareby'], 'cookie' => $_COOKIE['shareby' . $_W['weid']], 'fans' => $_W['fans']['from_user']));
        if ($_GPC['shareby'] != $_W['fans']['from_user']){
            $_link -> link($_W['weid'], $_W['fans']);
        }
    }
    public function doMobileList(){
        global $_GPC, $_W, $_SERVER;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $this -> tryLink();
        $this -> forceOpenInWechat();
        $title = $_W['account']['name'];
        if (!empty($_GPC['shareby']) && !empty($this -> module['config']['enable_inshop_mode'])){
            $shopowner = $_fans -> get($_W['weid'], $_GPC['shareby']);
            isetcookie('sharebyck', $_GPC['shareby'], 60 * 60 * 24 * 7);
            $shopowner['shopname'] = $this -> getShopname($shopowner['nickname']);
        }else if (!empty($_GPC['sharebyck']) && !empty($this -> module['config']['enable_inshop_mode'])){
            $shopowner = $_fans -> get($_W['weid'], $_GPC['sharebyck']);
            $shopowner['shopname'] = $this -> getShopname($shopowner['nickname']);
        }else{
            $shopowner = $_fans -> refresh($_W['weid'], $_W['fans']['from_user']);
            $shopowner['shopname'] = $this -> module['config']['shopname'];
            $shopowner['avatar'] = empty($this -> module['config']['inshop_logo']) ? $_W['attachurl'] . "headimg_{$_W['acid']}.jpg" : toimage($this -> module['config']['inshop_logo']);
            $shopowner['uid'] = '001';
        }
        $fans = $_fans -> refresh($_W['weid'], $_W['fans']['from_user']);
        $vip_cond = array('min_visible_level' => $fans['vip']);
        if (!empty($this -> module['config']['enable_single_goods_id'])){
            yload() -> classs('quickshop', 'goods');
            $_goods = new Goods();
            $item = $_goods -> get(intval($this -> module['config']['enable_single_goods_id']));
            yload() -> classs('quickcenter', 'template');
            $_template = new Template($this -> module['name']);
            $_W['account']['template'] = $this -> getTemplateName();
            $shareby_str = empty($_W['fans']['from_user']) ? '' : '&shareby=' . $_W['fans']['from_user'];
            $share = array();
            $share['title'] = empty($item['timelinetitle']) ? $item['title'] : $item['timelinetitle'];
            $share['content'] = empty($item['timelinedesc']) ? null : $item['timelinedesc'];
            $share['img'] = empty($item['timelinethumb']) ? null : $_W['attachurl'] . $item['timelinethumb'];
            yload() -> classs('quickcenter', 'wechatutil');
            $share['link'] = WechatUtil :: createMobileUrl('List', 'quickshop', array('shareby' => $_W['fans']['from_user']));
            if (!empty($_GPC['shareby'])){
                $share_fans = $_fans -> fans_search_by_openid($_W['weid'], $_GPC['shareby']);
            }
            $showSecret = false;
            if (!empty($item['secret_content']) && !empty($_W['fans']['from_user'])){
                $showSecret = $_goods -> hasBuy($_W['weid'], $_W['fans']['from_user'], $item['id']);
            }
            include $_template -> template('index');
            exit(0);
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 2000;
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'category');
        yload() -> classs('quickshop', 'advertise');
        $_goods = new Goods();
        $_category = new Category();
        $_advertise = new Advertise();
        $children = array();
        $category = $_category -> batchGet($_W['weid'], array('enabled' => 1), 'id');
        foreach ($category as $index => $row){
            if (!empty($row['parentid'])){
                $children[$row['parentid']][$row['id']] = $row;
                unset($category[$index]);
            }
        }
        $recommendcategory = array();
        foreach ($category as & $c){
            $vip_cond['isrecommend'] = 1;
            list($c['list'], $c['total']) = $_goods -> batchGetByPrimaryCategory($_W['weid'], $c['id'], $vip_cond, $pindex, $psize);
            $c['pager'] = pagination($c['total'], $pindex, $psize, $url = '', $context = array('before' => 0, 'after' => 0, 'ajaxcallback' => ''));
            $recommendcategory[] = $c;
        }
        unset($c);
        $carttotal = $this -> getCartTotal();
        $goodstotal = $_goods -> batchGetCount($_W['weid']);
        $newtotal = $_goods -> batchGetCount($_W['weid'], array('isnew' => 1));
        $advs = $_advertise -> batchGet($_W['weid']);
        foreach ($advs as & $adv){
            if (substr($adv['link'], 0, 5) != 'http:'){
                $adv['link'] = "http://" . $adv['link'];
            }
        }
        unset($adv);
        $rpindex = max(1, intval($_GPC['rpage']));
        $rpsize = 6;
        list($rlist, $rtotal) = $_goods -> batchGetByHot($_W['weid'], $vip_cond, $rpindex, $rpsize);
        $shareby_str = empty($_W['fans']['from_user']) ? '' : '&shareby=' . $_W['fans']['from_user'];
        $share = array();
        $share['title'] = (empty($fans['nickname'])) ? $shopowner['shopname'] : $this -> getShopname($fans['nickname']);
        $share['content'] = $this -> module['config']['inshop_share_text'];
        $share['img'] = empty($fans['avatar']) ? $shopowner['avatar'] : $fans['avatar'];
        yload() -> classs('quickcenter', 'wechatutil');
        $share['link'] = WechatUtil :: createMobileUrl('List', 'quickshop', array('shareby' => $_W['fans']['from_user']));
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('list');
    }
    public function doMobilelistmore_rec(){
        global $_GPC, $_W;
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        $pindex = max(1, intval($_GPC['page']));
        $psize = 6;
        $list = $_goods -> batchGetByRecommend($_W['weid'], $rpindex, $rpsize);
        include $this -> template('list_more');
    }
    public function doMobileList2(){
        global $_GPC, $_W;
        $this -> forceOpenInWechat();
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'category');
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $_goods = new Goods();
        $_category = new Category();
        $fans = $_fans -> refresh($_W['weid'], $_W['fans']['from_user']);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 600000;
        $cid = intval($_GPC['pcate']);
        $category = $_category -> get($cid);
        $vip_cond = array('min_visible_level' => $fans['vip']);
        list($list, $total) = $_goods -> batchGetByPrimaryCategory($_W['weid'], $cid, $vip_cond, $pindex, $psize);
        $title = $category['name'];
        $shareby_str = empty($_W['fans']['from_user']) ? '' : '&shareby=' . $_W['fans']['from_user'];
        $share = array();
        $share['title'] = $category['name'];
        $share['content'] = $category['description'];
        $share['img'] = '';
        yload() -> classs('quickcenter', 'wechatutil');
        $share['link'] = WechatUtil :: createMobileUrl('List2', 'quickshop', array('shareby' => $_W['fans']['from_user']));
        $carttotal = $this -> getCartTotal();
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('list2');
    }
    public function doMobileList3(){
        global $_GPC, $_W;
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        $categories = array('isnew', 'ishot', 'isdiscount', 'isrecommend', 'istime');
        if (in_array($_GPC['category'], $categories)){
        }elseif (empty($_GPC['category'])){
            message('没有指定显示类别', '', 'error');
        }
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('list3');
    }
    public function doMobileListByTag(){
        global $_GPC, $_W;
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        $categories = array('isnew', 'ishot', 'isdiscount', 'isrecommend', 'istime');
        if (in_array($_GPC['category'], $categories)){
        }elseif (empty($_GPC['category'])){
            message('没有指定显示类别', '', 'error');
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 600000;
        $cond = array($_GPC['category'] => 1);
        list($list, $total) = $_goods -> batchGet($_W['weid'], $cond, $pindex, $psize);
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('list_bytag');
    }
    public function doMobileSearch(){
        global $_GPC, $_W;
        yload() -> classs('quickshop', 'goods');
        $_goods = new Goods();
        if (empty($_GPC['keyword'])){
            $title = '商品搜索';
        }else{
            $title = $_GPC['keyword'] . ' - 商品搜索';
        }
        if (!empty($_GPC['keyword'])){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 200;
            list($list, $total) = $_goods -> batchGet($_W['weid'], $_GPC, $pindex, $psize);
            $pager = pagination($total, $pindex, $psize);
        }
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('search-result');
    }
    public function doMobileNav(){
        global $_GPC, $_W;
        $this -> forceOpenInWechat();
        $pindex = max(1, intval($_GPC['page']));
        $psize = 2000;
        yload() -> classs('quickshop', 'category');
        $_category = new Category();
        $children = array();
        $category = $_category -> batchGet($_W['weid'], array('enabled' => 1));
        foreach ($category as $index => $row){
            if (!empty($row['parentid'])){
                $children[$row['parentid']][$row['id']] = $row;
                unset($category[$index]);
            }
        }
        $title = '导航';
        $shareby_str = empty($_W['fans']['from_user']) ? '' : '&shareby=' . $_W['fans']['from_user'];
        $share = array();
        $share['title'] = $title;
        $share['content'] = $title;
        $share['img'] = '';
        yload() -> classs('quickcenter', 'wechatutil');
        $share['link'] = WechatUtil :: createMobileUrl('Nav', 'quickshop', array('shareby' => $_W['fans']['from_user']));
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('nav');
    }
    function time_tran($the_time){
        $timediff = $the_time - time();
        $days = intval($timediff / 86400);
        if (strlen($days) <= 1){
            $days = "0" . $days;
        }
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600); ;
        if (strlen($hours) <= 1){
            $hours = "0" . $hours;
        }
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        if (strlen($mins) <= 1){
            $mins = "0" . $mins;
        }
        $secs = $remain % 60;
        if (strlen($secs) <= 1){
            $secs = "0" . $secs;
        }
        $ret = "";
        if ($days > 0){
            $ret .= $days . " 天 ";
        }
        if ($hours > 0){
            $ret .= $hours . ":";
        }
        if ($mins > 0){
            $ret .= $mins . ":";
        }
        $ret .= $secs;
        return array("倒计时 " . $ret, $timediff);
    }
    public function doMobileMyCart(){
        global $_W, $_GPC;
        $title = $_W['account']['name'];
        $share = array();
        $share['disable'] = true;
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'cart');
        $_goods = new Goods();
        $_cart = new Cart();
        $this -> checkAuth();
        $op = $_GPC['op'];
        if ($op == 'add'){
            $result = 1;
            $goodsid = intval($_GPC['id']);
            $total = intval($_GPC['total']);
            $total = empty($total) ? 1 : $total;
            $goods = $_goods -> get($goodsid);
            if (empty($goods)){
                $result['message'] = '抱歉，该商品不存在或是已经被删除！';
                message($result, '', 'ajax');
            }
            $marketprice = $goods['marketprice'];
            $row = $_cart -> getByGoodsId($_W['weid'], $_W['fans']['from_user'], $goodsid);
            $t = $total + $row['total'];
            if (!empty($goods['maxbuy'])){
                if ($t > $goods['maxbuy']){
                    $result = 0;
                }
            }
            if ($result){
                if ($row == false){
                    $data = array('weid' => $_W['weid'], 'goodsid' => $goodsid, 'goodstype' => $goods['goodstype'], 'marketprice' => $marketprice, 'from_user' => $_W['fans']['from_user'], 'total' => $total, 'optionid' => $optionid);
                    $_cart -> create($data);
                }else{
                    $t = $total + $row['total'];
                    if (!empty($goods['maxbuy'])){
                        if ($t > $goods['maxbuy']){
                            $t = $goods['maxbuy'];
                        }
                    }
                    $_cart -> update($_W['weid'], $_W['fans']['from_user'], $row['id'], $t);
                }
            }
            $carttotal = $this -> getCartTotal();
            $result = array('result' => $result, 'total' => $carttotal, 'maxbuy' => $goods['maxbuy'],);
            die(json_encode($result));
        }else if ($op == 'clear'){
            $_cart -> clear($_W['weid'], $_W['fans']['from_user']);
            header('Location:' . $this -> createMobileUrl('MyCart'));
            exit(0);
        }else if ($op == 'remove'){
            $id = intval($_GPC['id']);
            $_cart -> remove($_W['weid'], $_W['fans']['from_user'], $id);
            header('Location:' . $this -> createMobileUrl('MyCart'));
            exit(0);
        }else if ($op == 'update'){
            $id = intval($_GPC['id']);
            $new_amount = intval($_GPC['num']);
            $_cart -> update($_W['weid'], $_W['fans']['from_user'], $id, $new_amount);
            die(json_encode(array("result" => 1)));
        }else{
            $list = $_cart -> batchGet($_W['weid'], $_W['fans']['from_user']);
            $totalprice = 0;
            if (!empty($list)){
                foreach ($list as & $item){
                    $goods = $_goods -> get($item['goodsid']);
                    $item['goods'] = $goods;
                    $item['totalprice'] = (floatval($goods['marketprice']) * intval($item['total']));
                    $totalprice += $item['totalprice'];
                }
                unset($item);
            }
            $carttotal = $this -> getCartTotal();
            yload() -> classs('quickcenter', 'template');
            $_template = new Template($this -> module['name']);
            $_W['account']['template'] = $this -> getTemplateName();
            include $_template -> template('cart');
        }
    }
    public function doMobileConfirm(){
        global $_W, $_GPC;
        $share = array();
        $share['disable'] = true;
        $this -> checkAuth();
        $title = $_W['account']['name'];
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'dispatch');
        yload() -> classs('quickshop', 'express');
        yload() -> classs('quickshop', 'address');
        yload() -> classs('quickshop', 'cart');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickcenter', 'fans');
        $_goods = new Goods();
        $_dispatch = new Dispatch();
        $_express = new Express();
        $_address = new Address();
        $_cart = new Cart();
        $_order = new Order();
        $_fans = new Fans();
        $totalprice = 0;
        $allgoods = array();
        $profile = $_fans -> get($_W['weid'], $_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile', 'credit1', 'credit2', 'vip', 'from_user'));
        $id = intval($_GPC['id']);
        $optionid = intval($_GPC['optionid']);
        $total = intval($_GPC['total']);
        if (empty($total)){
            $total = 1;
        }
        $direct = false;
        $returnurl = "";
        $goodstype = Goods :: $VIRTUAL_GOODS;
        $sendtype = Dispatch :: $PICKUP;
        if (!empty($id)){
            $item = $_goods -> get($id);
            $this -> checkGoodsTime($item);
            $this -> checkMaxBuy($_order, $_W['weid'], $_W['fans']['from_user'], $item['id'], $item['maxbuy'], $total, $item['title']);
            $this -> checkSoldOut($item['id'], $item['total'], $total, $item['title']);
            $item['stock'] = $item['total'];
            $item['total'] = $total;
            $item['totalprice'] = $total * $item['marketprice'];
            $allgoods[] = $item;
            $totalprice += $item['totalprice'];
            if ($item['goodstype'] == Goods :: $PHYSICAL_GOODS){
                $needdispatch = true;
                $goodstype = Goods :: $PHYSICAL_GOODS;
            }
            $sendtype = $item['sendtype'];
            $direct = true;
            $returnurl = $this -> createMobileUrl("confirm", array("id" => $id, "optionid" => $optionid, "total" => $total));
        }
        if (!$direct){
            $list = $_cart -> batchGet($_W['weid'], $_W['fans']['from_user']);
            if (!empty($list)){
                foreach ($list as & $g){
                    $item = $_goods -> get($g['goodsid']);
                    $this -> checkGoodsTime($item, 1);
                    $this -> checkMaxBuy($_order, $_W['weid'], $_W['fans']['from_user'], $item['id'], $item['maxbuy'], $g['total'], $item['title']);
                    $this -> checkSoldOut($item['id'], $item['total'], $g['total'], $item['title']);
                    $item['stock'] = $item['total'];
                    $item['total'] = $g['total'];
                    $item['totalprice'] = $g['total'] * $item['marketprice'];
                    $allgoods[] = $item;
                    $totalprice += $item['totalprice'];
                    if ($item['goodstype'] == Goods :: $PHYSICAL_GOODS){
                        $needdispatch = true;
                        $goodstype = Goods :: $PHYSICAL_GOODS;
                    }
                    if ($item['sendtype'] == Dispatch :: $EXPRESS){
                        $sendtype = Dispatch :: $EXPRESS;
                    }
                }
                unset($g);
            }
            $returnurl = $this -> createMobileUrl("confirm");
        }
        if (count($allgoods) <= 0){
            header("location: " . $this -> createMobileUrl('myorder'));
            exit();
        }
        $weight = 0;
        foreach ($allgoods as $g){
            $weight += $g['weight'] * $g['total'];
        }
        $dispatchprice = $this -> calcDispatchPrice($_dispatch, $_W['weid'], $weight);
        $totalprice += $dispatchprice;
        if (checksubmit('submit')){
            $addressid = intval($_GPC['addressid']);
            if (empty($_GPC['realname']) || empty($_GPC['mobile'])){
                message('请输完善您的资料！姓名:' . $_GPC['realname'] . ' 手机:' . $_GPC['mobile']);
            }
            if ($goodstype == Goods :: $PHYSICAL_GOODS && empty($_GPC['address'])){
                message('请输完善您的资料！姓名:' . $_GPC['realname'] . ' 手机:' . $_GPC['mobile'] . ' 地址:' . $_GPC['address']);
            }
            if (empty($_GPC['addressid'])){
                $data = array('isdefault' => 1, 'weid' => $_W['weid'], 'openid' => $_W['fans']['from_user'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'province' => $_GPC['province'], 'city' => $_GPC['city'], 'area' => $_GPC['area'], 'address' => $_GPC['address'],);
                $addressid = $_address -> create($data);
                $address = $_address -> get($addressid);
            }else{
                $data = array('isdefault' => 1, 'weid' => $_W['weid'], 'openid' => $_W['fans']['from_user'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'province' => $_GPC['province'], 'city' => $_GPC['city'], 'area' => $_GPC['area'], 'address' => $_GPC['address'],);
                if (false === ($address = $_address -> find($data))){
                    $addressid = $_address -> addDefault($_W['weid'], $_W['fans']['from_user'], $data);
                    $address = $_address -> get($addressid);
                }
            }
            if (empty($address)){
                message('抱歉，请您填写收货地址！');
            }
            $discount = 0;
            $creditused = 0;
            if (1 == intval($_GPC['usecredit'])){
                foreach ($allgoods as $row){
                    $discount += $row['max_coupon_credit'] * $row['total'];
                }
                $user_owned_max_discount = floatval($profile['credit1']) / 100;
                $discount = min($user_owned_max_discount, $discount);
                $creditused = $discount * 100;
            }
            if (1 == intval($_GPC['usecredit'])){
                $pending_conds = array('from_user' => $profile['from_user']);
                list($pending_list, $pending_total) = $_order -> batchGetNew($_W['weid'], $pending_conds, null, 1, 10000000);
                $pendding_credit = 0;
                foreach ($pending_list as $p){
                    $pendding_credit += $p['creditused'];
                }
                if ($profile['credit1'] < $pendding_credit + $creditused){
                    if ($pendding_credit > 0){
                        message('对不起，你还有未完成的订单，请先支付这些订单。', $this -> createMobileUrl('MyOrder'), 'error');
                    }else{
                        message('对不起，你的积分不够，无法完成抵扣', '', 'error');
                    }
                }
            }
            $goodsprice = 0;
            foreach ($allgoods as $row){
                $this -> checkSoldOut($row['id'], $row['stock'], $row['total'], $row['title']);
                if ($row['totalcnf'] == 3){
                    $this -> checkMaxOrderedGoodsCount($_order, $_W['weid'], $_W['fans']['from_user'], $row['id'], $row['maxbuy'], $row['total'], $row['title']);
                    $this -> setGoodsStock($row['id'], $row['totalcnf'], $row['stock'], $row['total'], $row['sales']);
                }
                $goodsprice += $row['totalprice'];
            }
            $data = array('weid' => $_W['weid'], 'from_user' => $_W['fans']['from_user'], 'ordersn' => date('mdHis') . random(5, 1), 'price' => $goodsprice + $dispatchprice - $discount, 'dispatchprice' => $dispatchprice, 'goodsprice' => $goodsprice, 'discount' => $discount, 'usecredit' => intval($_GPC['usecredit']), 'creditused' => $creditused, 'status' => Order :: $ORDER_NEW, 'sendtype' => intval($_GPC['sendtype']), 'dispatch' => $dispatchid, 'paytype' => Order :: $PAY_ONLINE, 'goodstype' => $goodstype, 'remark' => $_GPC['remark'], 'addressid' => $address['id'], 'createtime' => TIMESTAMP, 'updatetime' => TIMESTAMP,);
            $orderid = $_order -> create($data);
            foreach ($allgoods as $row){
                if (empty($row)){
                    continue;
                }
                $d = array('weid' => $_W['weid'], 'goodsid' => $row['id'], 'orderid' => $orderid, 'total' => $row['total'], 'ordergoodsprice' => $row['marketprice'], 'createtime' => TIMESTAMP, 'optionid' => $row['optionid']);
                $_order -> addGoods($d);
            }
            if (!$direct){
                $_cart -> clear($_W['weid'], $_W['fans']['from_user']);
            }
            die("<script>location.href='" . $this -> createMobileUrl('pay', array('orderid' => $orderid)) . "';</script>");
        }
        $carttotal = $this -> getCartTotal();
        $row = $_address -> getDefault($_W['weid'], $_W['fans']['from_user']);
        $totalcredit = 0;
        $allgoods_id = array();
        foreach($allgoods as $g){
            $allgoods_id[] = $g['id'];
            $totalcredit += $g['max_coupon_credit'] * $g['total'];
        }
        $totalcredit = min($totalcredit, $profile['credit1'] / 100.0);
        $carttotal = $this -> getCartTotal();
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('confirm');
    }
    public function setOrderCredit($orderid, $add = true){
        global $_W;
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickcenter', 'fans');
        $_goods = new Goods();
        $_order = new Order();
        $_fans = new Fans();
        $order = $_order -> get($orderid);
        if (empty($order)){
            return;
        }
        if (Order :: $PAY_ONLINE == $order['paytype'] or Order :: $PAY_CREDIT == $order['paytype']){
            $ordergoods = $_order -> getGoods($orderid, 'goodsid');
            if (!empty($ordergoods)){
                $goods = $_goods -> batchGetByIds($_W['weid'], array_keys($ordergoods));
            }
            if (!empty($goods)){
                $credits = 0;
                foreach ($goods as $g){
                    $credits += $g['credit'];
                }
                if ($credits > 0){
                    if ($add){
                        $_fans -> addCredit($_W['weid'], $order['from_user'], $credits, 1, '购物送积分');
                    }else{
                        $_fans -> addCredit($_W['weid'], $order['from_user'], 0 - $credits, 1, '取消订单减积分');
                    }
                }
            }
            if (!empty($goods)){
                $credits = 0;
                foreach ($goods as $g){
                    $credits += $g['credit2'];
                }
                if ($credits > 0){
                    if ($add){
                        $_fans -> addCredit($_W['weid'], $order['from_user'], $credits, 2, '购物返现金');
                    }else{
                        $_fans -> addCredit($_W['weid'], $order['from_user'], 0 - $credits, 2, '取消订单扣积分');
                    }
                }
            }
        }
        $discount_credit = $order['creditused'];
        if ($discount_credit > 0){
            if ($add){
                $_fans -> addCredit($_W['weid'], $order['from_user'], 0 - $discount_credit, 1, '积分换购消耗积分');
            }else{
                $_fans -> addCredit($_W['weid'], $order['from_user'], $discount_credit, 1, '取消订单, 送还换购积分');
            }
        }
    }
    public function doMobilePay(){
        global $_W, $_GPC;
        $share = array();
        $share['disable'] = true;
        $this -> checkAuth();
        $title = $_W['account']['name'];
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $orderid = intval($_GPC['orderid']);
        $order = $_order -> get($orderid);
        if (empty($order)){
            message('非法订单!', '', 'error');
        }
        if ($order['status'] != Order :: $ORDER_NEW){
            message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this -> createMobileUrl('myorder'), 'error');
        }
        $allgoods = $_order -> getDetailedGoods($orderid);
        $isSupportDelivery = true;
        foreach ($allgoods as $row){
            if ($row['support_delivery'] == 0){
                $isSupportDelivery = false;
                $_W['account']['payment']['delivery']['switch'] = 'OFF';
            }
            $this -> checkMaxBuy($_order, $_W['weid'], $_W['fans']['from_user'], $row['id'], $row['maxbuy'], $row['total'], $row['title']);
        }
        $params['tid'] = $orderid;
        $params['user'] = $order['from_user'];
        $params['fee'] = $order['price'];
        $params['title'] = $_W['account']['name'];
        $params['ordersn'] = $order['ordersn'];
        $carttotal = $this -> getCartTotal();
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('pay');
    }
    public function doMobileContactUs(){
        global $_W;
        $cfg = $this -> module['config'];
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('contactus');
    }
    public function doMobileMyOrder(){
        global $_W, $_GPC;
        $share = array();
        $share['disable'] = true;
        $title = $_W['account']['name'];
        $this -> checkAuth();
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickshop', 'dispatch');
        yload() -> classs('quickshop', 'address');
        $_order = new Order();
        $_dispatch = new Dispatch();
        $_address = new Address();
        $op = $_GPC['op'];
        if ($op == 'remove'){
            if (empty($this -> module['config']['enable_user_remove_order'])){
                message('抱歉，暂不支持删除订单！', $this -> createMobileUrl('myorder'), 'error');
            }
            $orderid = intval($_GPC['orderid']);
            $order = $_order -> get($orderid);
            if (empty($order)){
                message('抱歉，您的订单不存或是已经被取消！', $this -> createMobileUrl('myorder'), 'error');
            }
            if ($order['status'] != Order :: $ORDER_NEW and $order['status'] != Order :: $ORDER_CANCEL){
                message('抱歉，仅支持删除尚未付款或已取消订单', $this -> createMobileUrl('myorder'), 'error');
            }
            if ($order['status'] == Order :: $ORDER_NEW or $order['status'] == Order :: $ORDER_CANCEL){
                $_order -> clientRemove($_W['weid'], $_W['fans']['from_user'], $orderid);
            }
            header('Location:' . $this -> createMobileUrl('MyOrder'));
            exit(0);
        }else if ($op == 'confirm'){
            $orderid = intval($_GPC['orderid']);
            $order = $_order -> get($orderid);
            if (empty($order)){
                message('抱歉，您的订单不存或是已经被取消！', $this -> createMobileUrl('myorder'), 'error');
            }
            if ($order['status'] != Order :: $ORDER_RECEIVED){
                $_order -> clientUpdate($_W['weid'], $_W['fans']['from_user'], $orderid, array('status' => Order :: $ORDER_RECEIVED));
                $this -> notifyUser($_W['weid'], $orderid, 'notifyReceived');
                if (Order :: $PAY_DELIVERY == $order['paytype']){
                    yload() -> classs('quickcenter', 'fans');
                    $_fans = new Fans();
                    $_fans -> setVIP($order['weid'], $order['from_user']);
                }
            }
            message('确认成功！', $this -> createMobileUrl('myorder'), 'success');
        }else if ($op == 'detail'){
            $orderid = intval($_GPC['orderid']);
            $item = $_order -> clientGet($_W['uniacid'], $_W['fans']['from_user'], $orderid);
            if (empty($item)){
                message('抱歉，您的订单不存或是已经被取消！订单号:' . $orderid, $this -> createMobileUrl('myorder'), 'error');
            }
            $item['goods'] = $_order -> getDetailedGoods($item['id']);
            $item['total'] = 199999;
            $item['address'] = $_address -> get($item['addressid']);
            $carttotal = $this -> getCartTotal();
            yload() -> classs('quickcenter', 'template');
            $_template = new Template($this -> module['name']);
            $_W['account']['template'] = $this -> getTemplateName();
            include $_template -> template('order-detail');
        }else{
            $pindex = max(1, intval($_GPC['page']));
            $psize = 999;
            $status = intval($_GPC['status']);
            list($list, $total) = $_order -> batchGet($_W['weid'], array('from_user' => $_W['fans']['from_user'], 'status' => $status), 'id', $pindex, $psize);
            $pager = pagination($total, $pindex, $psize);
            if (!empty($list)){
                foreach ($list as & $row){
                    $goods = $_order -> getDetailedGoods($row['id']);;
                    $row['goods'] = $goods;
                    $row['total'] = 199999;
                    $row['dispatch'] = $_dispatch -> get($row['dispatch']);
                    $row['address'] = $_address -> get($row['addressid']);
                }
            }
            $carttotal = $this -> getCartTotal();
            yload() -> classs('quickcenter', 'template');
            $_template = new Template($this -> module['name']);
            $_W['account']['template'] = $this -> getTemplateName();
            include $_template -> template('order');
        }
    }
    public function doMobileDetail(){
        global $_W, $_GPC;
        $this -> tryLink();
        $title = $_W['account']['name'];
        yload() -> classs('quickshop', 'goods');
        yload() -> classs('quickcenter', 'fans');
        $_goods = new Goods();
        $_fans = new Fans();
        $fans = $_fans -> refresh($_W['weid'], $_W['fans']['from_user']);
        $goodsid = intval($_GPC['id']);
        $goods = $_goods -> get($goodsid);
        if (empty($goods)){
            message('抱歉，商品不存在或是已经被删除！');
        }
        $_goods -> updateViewCount($_W['weid'], $goodsid);
        if ($goods['thumb_url'] != 'N;'){
            $piclist = unserialize($goods['thumb_url']);
        }
        if (empty($piclist)){
            $piclist[] = $goods['thumb'];
        }
        $marketprice = $goods['marketprice'];
        $productprice = $goods['productprice'];
        $stock = $goods['total'];
        $timelast = intval($goods['timeend'] - TIMESTAMP);
        $timewait = intval($goods['timestart'] - TIMESTAMP);
        $carttotal = $this -> getCartTotal();
        $share = array();
        $share['title'] = empty($goods['timelinetitle']) ? $goods['title'] : $goods['timelinetitle'];
        $share['content'] = empty($goods['timelinedesc']) ? null : $goods['timelinedesc'];
        $share['img'] = empty($goods['timelinethumb']) ? null : $_W['attachurl'] . $goods['timelinethumb'];
        yload() -> classs('quickcenter', 'wechatutil');
        $share['link'] = WechatUtil :: createMobileUrl('Detail', 'quickshop', array('id' => $goodsid, 'shareby' => $_W['fans']['from_user']));
        if (!empty($goods['spec']) and 1){
            yload() -> classs('quickshop', 'specparser');
            $specs = SpecParser :: parse($_W['weid'], $goods['spec']);
        }
        $showSecret = false;
        if (!empty($goods['secret_content']) && !empty($_W['fans']['from_user'])){
            $showSecret = $_goods -> hasBuy($_W['weid'], $_W['fans']['from_user'], $goodsid);
        }
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('detail');
    }
    public function doMobileAddress(){
        global $_W, $_GPC;
        $share = array();
        $share['disable'] = true;
        $title = $_W['account']['name'];
        yload() -> classs('quickshop', 'address');
        $_address = new Address();
        $from = $_GPC['from'];
        $returnurl = urldecode($_GPC['returnurl']);
        $this -> checkAuth();
        $operation = $_GPC['op'];
        if ($operation == 'post'){
            $id = intval($_GPC['id']);
            $data = array('weid' => $_W['weid'], 'openid' => $_W['fans']['from_user'], 'realname' => $_GPC['realname'], 'mobile' => $_GPC['mobile'], 'province' => $_GPC['province'], 'city' => $_GPC['city'], 'area' => $_GPC['area'], 'address' => $_GPC['address'],);
            if (empty($_GPC['realname']) || empty($_GPC['mobile']) || empty($_GPC['address'])){
                message('请输完善您的资料！');
            }
            if (!empty($id)){
                unset($data['weid']);
                unset($data['openid']);
                $_address -> update($_W['weid'], $id, $data);
                message($id, '', 'ajax');
            }else{
                $id = $_address -> addDefault($_W['weid'], $_W['fans']['from_user'], $data);
                if (!empty($id)){
                    message($id, '', 'ajax');
                }else{
                    message(0, '', 'ajax');
                }
            }
        }elseif ($operation == 'default'){
            $id = intval($_GPC['id']);
            $_address -> changeDefault($_W['weid'], $_W['fans']['from_user'], $id);
            message(1, '', 'ajax');
        }elseif ($operation == 'detail'){
            $id = intval($_GPC['id']);
            $row = $_address -> get($id);
            message($row, '', 'ajax');
        }elseif ($operation == 'remove'){
            $id = intval($_GPC['id']);
            $default = 0;
            if (!empty($id)){
                $address = $_address -> clientGet($_W['uniacid'], $_W['fans']['from_user'], $id);
                if (!empty($address)){
                    $default = $_address -> markDelete($weid, $_W['fans']['from_user'], $id, $address['isdefault']);
                }
            }
            die(json_encode(array("result" => 1, "maxid" => $default)));
        }else{
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            $profile = $_fans -> fans_search_by_openid($_W['weid'], $_W['fans']['from_user'], array('resideprovince', 'residecity', 'residedist', 'address', 'realname', 'mobile'));
            $address = $_address -> batchGet($_W['weid'], array('openid' => $_W['fans']['from_user']));
            $carttotal = $this -> getCartTotal();
            yload() -> classs('quickcenter', 'template');
            $_template = new Template($this -> module['name']);
            $_W['account']['template'] = $this -> getTemplateName();
            include $_template -> template('address');
        }
    }
    private function checkAuth(){
        global $_W;
        $this -> MyCheckauth();
    }
    private function MyCheckauth($redirect = true){
        global $_W;
        if (empty($_W['fans']['from_user'])){
            if ($redirect){
                $follow = $this -> module['config']['followurl'];
                if (!empty($follow)){
                    header('Location: ' . $follow);
                    exit();
                }else{
                    checkauth();
                }
            }
        }
    }
    public function payResult2(){
        yload() -> classs('quickcenter', 'wechatutil');
        $url = WechatUtil :: createMobileUrl('myorder', 'quickshop');
        $this -> message('微信支付成功！', $url, 'success');
    }
    public function payResult($params){
        global $_W;
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $order = $_order -> get($params['tid']);
        yload() -> classs('quickcenter', 'wechatutil');
        $url = WechatUtil :: createMobileUrl('myorder', 'quickshop');
        if ($params['type'] == 'wechat'){
            if ($params['from'] == 'return'){
                if ($order['status'] == Order :: $ORDER_PAYED){
                    WeUtility :: logging('payResultConfirm Payed already', $params);
                    $this -> message('微信支付成功！', $url, 'success');
                }else if ($order['status'] == Order :: $ORDER_NEW){
                    if ($params['result'] != 'success'){
                        $this -> message('微信支付失败，请检查订单状态，如显示为未支付，请联系客服！错误码：' . $params['result'], $url, 'success');
                    }else{
                        $this -> onOrderPayedSuccess($_order, $params['uniacid'], $params['user'], $params['tid'], $params['tag']['transaction_id'], Order :: $PAY_ONLINE);
                        WeUtility :: logging('payResult Pay Done', $params);
                        $this -> mn = 'quickshop';
                        $this -> message('微信支付成功！', $url, 'success');
                    }
                }
            }
            if ($params['from'] == 'notify'){
                if ($order['status'] == Order :: $ORDER_PAYED){
                    return;
                }else if ($order['status'] == Order :: $ORDER_NEW){
                    if ($params['result'] == 'success'){
                        $this -> onOrderPayedSuccess($_order, $params['uniacid'], $params['user'], $params['tid'], $params['tag']['transaction_id'], Order :: $PAY_ONLINE);
                    }else{
                        $this -> onOrderPayedFail($_order, $params['uniacid'], $params['user'], $params['tid'], $params['tag']['transaction_id']);
                    }
                    WeUtility :: logging('payResultNotify Pay Done', $params);
                }
            }
        }
        if ($params['type'] == 'delivery'){
            if ($params['from'] == 'return'){
                if ($order['status'] == Order :: $ORDER_PAYED){
                    WeUtility :: logging('payResultConfirm Payed(delivery) already', $params);
                    $this -> message('已经下单成功！', $url, 'success');
                }else if ($order['status'] == Order :: $ORDER_NEW){
                    if ($params['result'] == 'success'){
                        $this -> message('货到付款支付失败，请检查订单状态，如显示为未支付，请联系客服！', $url, 'success');
                    }else{
                        $_order -> clientUpdate($_W['weid'], $order['from_user'], $order['id'], array('status' => Order :: $ORDER_PAYED, 'paytype' => Order :: $PAY_DELIVERY));
                        $transid = '';
                        $this -> onOrderPayedSuccess($_order, $order['weid'], $order['from_user'], $order['id'], $transid, Order :: $PAY_DELIVERY);
                        message('恭喜您，订单提交成功，我们会尽快与您取得联系，请保持电话畅通！', $this -> createMobileUrl('myorder'), 'success');
                    }
                }
            }
        }
        if ($params['type'] == 'credit'){
            if ($params['from'] == 'return'){
                if ($order['status'] == Order :: $ORDER_PAYED){
                    WeUtility :: logging('payResultConfirm Payed(delivery) already', $params);
                    $this -> message('已经下单成功！', $url, 'success');
                }else if ($order['status'] == Order :: $ORDER_NEW){
                    if ($params['result'] != 'success'){
                        $this -> message('余额付款支付失败，请检查订单状态，如显示为未支付，请联系客服！', $url, 'success');
                    }else{
                        $_order -> clientUpdate($order['weid'], $order['from_user'], $order['id'], array('status' => Order :: $ORDER_PAYED, 'paytype' => Order :: $PAY_CREDIT));
                        $transid = '';
                        $this -> onOrderPayedSuccess($_order, $order['weid'], $order['from_user'], $order['id'], $transid, Order :: $PAY_CREDIT);
                        $this -> message('余额支付成功！', $url, 'success');
                    }
                }
            }
        }
    }
    private function onOrderPayedSuccess($_order, $weid, $from_user, $orderid, $transid, $paytype){
        yload() -> classs('quickcenter', 'wechatsetting');
        $_setting = new WechatSetting();
        $setting = $_setting -> get($weid, 'quickshop');
        $data = array('status' => Order :: $ORDER_PAYED, 'transid' => $transid);
        $_order -> update($weid, $orderid, $data);
        if (false and !empty($setting['noticeemail'])){
            yload() -> classs('quickshop', 'orderemail');
            $_mail = new OrderEmail();
            $_mail -> send($order);
        }
        if (Order :: $PAY_ONLINE == $paytype or Order :: $PAY_CREDIT == $paytype){
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            $_fans -> setVIP($weid, $from_user);
        }
        $this -> setOrderCredit($orderid);
        $this -> setOrderStock($orderid, true);
        $param = array('weid' => $weid, 'orderid' => $orderid, 'template_id' => $setting['payed_template_id']);
        WeUtility :: logging('going to push to queue', $param);
        if (0){
            yload() -> classs('quickdynamics', 'messagequeue');
            $mq = new MessageQueue();
            $mq -> push('quickshop', 'ordernotifier', 'OrderNotifier', 'notifyPayed', $param);
        }else{
            yload() -> classs('quickshop', 'ordernotifier');
            $_notifier = new OrderNotifier();
            $_notifier -> notifyPayed($param);
        }
        if (1){
            yload() -> classs('quickdynamics', 'messagequeue');
            $mq = new MessageQueue();
            $mq -> push('quickshop', 'ordernotifier', 'OrderNotifier', 'notifyQR', $param);
        }
    }
    private function onOrderPayedFail($_order, $weid, $from_user, $orderid, $transid){
        $data = array('status' => Order :: $ORDER_FAIL, 'transid' => $transid);
        $_order -> update($weid, $orderid, $data);
    }
    private function notifyUser($weid, $orderid, $method){
        yload() -> classs('quickcenter', 'wechatsetting');
        $_setting = new WechatSetting();
        $setting = $_setting -> get($weid, 'quickshop');
        yload() -> classs('quickdynamics', 'messagequeue');
        $param = array('weid' => $weid, 'orderid' => $orderid, 'template_id' => $setting['payed_template_id']);
        WeUtility :: logging('going to push to queue', $param);
        $mq = new MessageQueue();
        $mq -> push('quickshop', 'ordernotifier', 'OrderNotifier', $method, $param);
    }
    public function doWebDispatch(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickshop', 'dispatch');
        $_dispatch = new Dispatch();
        $id = intval($_GPC['id']);
        if (checksubmit('submit')){
            $data = array('weid' => $_W['weid'], 'displayorder' => intval($_GPC['dispatch_name']), 'dispatchtype' => intval($_GPC['dispatchtype']), 'dispatchname' => $_GPC['dispatchname'], 'express' => $_GPC['express'], 'firstprice' => $_GPC['firstprice'], 'firstweight' => $_GPC['firstweight'], 'secondprice' => $_GPC['secondprice'], 'secondweight' => $_GPC['secondweight'], 'description' => $_GPC['description'], 'province' => $_GPC['sel-provance'], 'city' => $_GPC['sel-city'], 'area' => $_GPC['sel-area'],);
            if (!empty($id)){
                $_dispatch -> update($_W['weid'], $id, $data);
            }else{
                $id = $_dispatch -> create($data);
            }
            message('更新快递模板成功！', $this -> createWebUrl('dispatch'), 'success');
        }
        if (!empty($id)){
            $dispatch = $_dispatch -> get($id);
        }else{
            $dispatchs = $_dispatch -> batchGet($_W['weid']);
            $dispatch = $dispatchs[0];
        }
        include $this -> template('dispatch', TEMPLATE_INCLUDEPATH, true);
    }
    public function doWebAdv(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        load() -> func('tpl');
        yload() -> classs('quickcenter', 'FormTpl');
        yload() -> classs('quickshop', 'advertise');
        $_advertise = new Advertise();
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display'){
            $conds = array('display' => 'all');
            $list = $_advertise -> batchGet($_W['weid'], $conds);
        }elseif ($operation == 'post'){
            $id = intval($_GPC['id']);
            if (checksubmit('submit')){
                $data = array('weid' => $_W['weid'], 'advname' => $_GPC['advname'], 'link' => $_GPC['link'], 'enabled' => intval($_GPC['enabled']), 'displayorder' => intval($_GPC['displayorder']), 'thumb' => $_GPC['thumb']);
                if (!empty($id)){
                    $_advertise -> update($_W['weid'], $id, $data);
                }else{
                    $id = $_advertise -> create($data);
                }
                message('更新幻灯片成功！', $this -> createWebUrl('adv', array('op' => 'display')), 'success');
            }
            $adv = $_advertise -> get($id);
        }elseif ($operation == 'delete'){
            $id = intval($_GPC['id']);
            $adv = $_advertise -> get($id);
            if (empty($adv)){
                message('抱歉，幻灯片不存在或是已经被删除！', $this -> createWebUrl('adv', array('op' => 'display')), 'error');
            }
            $_advertise -> remove($_W['weid'], $id);
            message('幻灯片删除成功！', $this -> createWebUrl('adv', array('op' => 'display')), 'success');
        }else{
            message('请求方式不存在');
        }
        include $this -> template('adv', TEMPLATE_INCLUDEPATH, true);
    }
    public function doMobileAjaxdelete(){
        global $_GPC;
        $delurl = $_GPC['pic'];
        ob_clean();
        if (file_delete($delurl)){
            echo 1;
        }else{
            echo 0;
        }
    }
    private function getTemplateName(){
        if (empty($this -> module['config']['template'])){
            return 'pink';
        }
        return $this -> module['config']['template'];
    }
    private function message($msg, $redirect, $label){
        global $_W;
        yload() -> classs('quickcenter', 'template');
        $_template = new Template($this -> module['name']);
        $_W['account']['template'] = $this -> getTemplateName();
        include $_template -> template('message');
    }
    public function doWebAuth(){
        global $_W, $_GPC;
        yload() -> classs('quickauth', 'auth');
        $_auth = new Auth();
        $op = trim($_GPC['op']);
        $modulename = MODULE_NAME;
        $version = '0.60';
        $_auth -> checkXAuth($op, $modulename, $version);
        yload() -> classs('quickcenter', 'dependencychecker');
        $_checker = new DependencyChecker();
        $_checker -> requireModules($_W['account']['modules'], array('quickdynamics'));
    }
    public function doWebDownloadOrder(){
        yload() -> routing('quickshop', 'download');
    }
    private function checkSoldOut($goodsid, $total, $amount, $title){
        if ($total != -1){
            if ($total < $amount){
                $url = $this -> createMobileUrl('Detail', array('id' => $goodsid));
                if ($total <= 0){
                    message("抱歉，您购买本商品【{$title}】已经售罄，无法购买啦！", $url, "error");
                }else{
                    message("抱歉，您购买的商品【{$title}】库存不足，无法购买多件，请重新下单！", $url, "error");
                }
            }
        }
    }
    private function checkMaxOrderedGoodsCount($_order, $weid, $from_user, $goodsid, $maxbuy, $buyamount, $title){
        if (!empty($maxbuy)){
            $maxBuyed = $_order -> getTotalBuy($weid, $from_user, $goodsid);
            $newOrderCount = $_order -> getTotalNew($weid, $from_user, $goodsid);
            $url = $this -> createMobileUrl('Detail', array('id' => $goodsid));
            $order_url = $this -> createMobileUrl('MyOrder');
            if ($maxbuy <= $maxBuyed){
                message("抱歉，您购买本商品【{$title}】的数量已经达到了最大限额，无法购买啦！", $url, "error");
            }else if ($maxbuy <= $maxBuyed + $newOrderCount){
                message("抱歉，您购买的【{$title}】订单尚未支付，请先进入订单页面支付！", $order_url, "error");
            }else if ($maxbuy < $buyamount + $maxBuyed + $newOrderCount){
                message("抱歉，您购买本商品【{$title}】的数量超过了最大限额，请减少数量重新下单。", $url, "error");
            }
        }
        return true;
    }
    private function checkMaxBuy($_order, $weid, $from_user, $goodsid, $maxbuy, $buyamount, $title){
        if (!empty($maxbuy)){
            $maxBuyed = $_order -> getTotalBuy($weid, $from_user, $goodsid);
            $url = $this -> createMobileUrl('Detail', array('id' => $goodsid));
            if ($maxbuy <= $maxBuyed){
                message("抱歉，您购买本商品【{$title}】的数量已经达到了最大限额，无法购买啦！", $url, "error");
            }else if ($maxbuy < $buyamount + $maxBuyed){
                message("抱歉，您购买本商品【{$title}】的数量超过了最大限额，请减少数量重新下单。", $url, "error");
            }
        }
        return true;
    }
    private function checkGoodsTime($item, $isBatch = false){
        if ($item['istime'] == 1){
            $url = $this -> createMobileUrl('Detail', array('id' => $item['id']));
            if (TIMESTAMP < $item['timestart']){
                message('抱歉，' . $item['title'] . '还未到购买时间, 暂时无法购物哦~', $url, "error");
            }
            if (TIMESTAMP > $item['timeend']){
                message('抱歉，' . $item['title'] . '限购时间已到，不能购买了哦~', $url, "error");
            }
        }
    }
    private function call_debug_backtrace(){
        $traces = debug_backtrace();
        $ts = '';
        foreach($traces as $trace){
            $trace['file'] = str_replace('\\', '/', $trace['file']);
            $trace['file'] = str_replace(IA_ROOT, '', $trace['file']);
            $ts .= "file: {$trace['file']}; line: {$trace['line']}; <br />";
        }
        return $ts;
    }
    private function confirmSend($id){
        global $_GPC, $_W;
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $item = $_order -> get($id);
        $result = array();
        if (empty($item)){
            $result['message'] = '抱歉，商品不存在或是已经被删除！';
            $result['errno'] = 1;
            return $result;
        }
        if (empty($_GPC['expresssn'])){
            $result['message'] = '请输入快递单号';
            $result['errno'] = 2;
            return $result;
        }
        if (empty($_GPC['express']) or empty($_GPC['expresscom'])){
            $result['message'] = '请选择快递公司';
            $result['errno'] = 3;
            return $result;
        }
        $item = $_order -> get($id);
        if (Order :: $ORDER_PAYED != $item['status']){
            $result['message'] = '改订单不是已支付状态，无法直接发货。';
            $result['errno'] = 4;
            return $result;
        }
        $data = array('status' => Order :: $ORDER_DELIVERED, 'remark' => $item['remark'] . $_GPC['remark'], 'express' => $_GPC['express'], 'expresscom' => $_GPC['expresscom'], 'expresssn' => $_GPC['expresssn'],);
        $_order -> update($_W['weid'], $id, $data);
        $this -> notifyUser($_W['weid'], $id, 'notifyDelivered');
        $result['message'] = '发货操作成功！';
        $result['errno'] = 0;
        return $result;
    }
    public function doWebAjaxConfirmSend(){
        global $_GPC, $_W;
        ob_clean();
        yload() -> classs('quickshop', 'order');
        if (false && is_array($_GPC['id'])){
            foreach ($_GPC['id'] as $id){
                $result = $this -> confirmSend($id);
                if ($result['errno'] != 0){
                    message($result, '', 'ajax');
                }
            }
            message($result, '', 'ajax');
        }else{
            $id = intval($_GPC['id']);
            $result = $this -> confirmSend($id);
            message($result, '', 'ajax');
        }
    }
    public function doWebBatchOrder(){
        global $_W, $_GPC;
        $this -> doWebAuth();
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'dispatch');
        yload() -> classs('quickshop', 'address');
        yload() -> classs('quickshop', 'express');
        yload() -> classs('quickcenter', 'FormTpl');
        $_order = new Order();
        $_dispatch = new Dispatch();
        $_address = new Address();
        $_express = new Express();
        $operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        if ($operation == 'display'){
            $pindex = max(1, intval($_GPC['page']));
            $psize = 20000;
            $status = Order :: $ORDER_PAYED;
            $sendtype = Dispatch :: $EXPRESS;
            $conds = array('status' => $status, 'sendtype' => $sendtype);
            list($list, $total) = $_order -> batchGet($_W['weid'], $conds, null, $pindex, $psize);
            $pager = pagination($total, $pindex, $psize);
            if (!empty($list)){
                foreach ($list as & $row){
                    !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
                    $row['dispatch'] = $_dispatch -> get($row['dispatch']);
                }
                unset($row);
            }
            if (!empty($addressids)){
                $address = $_address -> batchGetByIds($_W['weid'], $addressids, 'id');
            }
            $status_text = $_order -> getOrderStatusName($status);
        }
        include $this -> template('batchorder');
    }
    private function forceOpenInWechat(){
        if(DEVELOPMENT){
            return;
        }
        yload() -> classs('quickcenter', 'wechatservice');
        $_weservice = new WechatService('quickshop');
        $fakeopenid = $_weservice -> forceOpenInWechat('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    }
    private function calcDispatchPrice($_dispatch, $weid, $weight){
        $d = $_dispatch -> getUnique($weid);
        if (empty($d)){
            $d['firstweight'] = $d['firstprice'] = $d['secondweight'] = $d['secondprice'] = 0;
        }
        $dispatchprice = 0;
        if ($weight <= 0){
            $dispatchprice = 0;
        }else if ($weight <= $d['firstweight']){
            $dispatchprice = $d['firstprice'];
        }else{
            $dispatchprice = $d['firstprice'];
            if ($d['secondweight'] > 0){
                $secondweight = $weight - $d['firstweight'];
                if ($secondweight % $d['secondweight'] == 0){
                    $dispatchprice += (int) ($secondweight / $d['secondweight']) * $d['secondprice'];
                }else{
                    $dispatchprice += (int) ($secondweight / $d['secondweight'] + 1) * $d['secondprice'];
                }
            }
        }
        unset($d);
        return $dispatchprice;
    }
    protected function pay($params = array(), $mine = array()){
        global $_W;
        if(!$this -> inMobile){
            message('支付功能只能在手机上使用');
        }
        if (empty($_W['member']['uid'])){
        }
        $params['module'] = $this -> module['name'];
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':module'] = $params['module'];
        $pars[':tid'] = $params['tid'];
        if($params['fee'] <= 0){
            $pars['from'] = 'return';
            $pars['result'] = 'success';
            $pars['type'] = 'alipay';
            $pars['tid'] = $params['tid'];
            $site = WeUtility :: createModuleSite($pars[':module']);
            $method = 'payResult';
            if (method_exists($site, $method)){
                exit($site -> $method($pars));
            }
        }
        $sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
        $log = pdo_fetch($sql, $pars);
        if(!empty($log) && $log['status'] == '1'){
            message('这个订单已经支付成功, 不需要重复支付.');
        }
        $setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
        if(!is_array($setting['payment'])){
            message('没有有效的支付方式, 请联系网站管理员.');
        }
        $pay = $setting['payment'];
        $pay['delivery']['switch'] = ($_W['account']['payment']['delivery']['switch'] == 'OFF') ? 0 : $pay['delivery']['switch'];
        if (empty($_W['member']['uid'])){
            $pay['credit']['switch'] = 0;
        }
        if (!empty($pay['credit']['switch'])){
            $credtis = mc_credit_fetch($_W['member']['uid']);
        }
        include $this -> template('common/paycenter');
    }
    private function getShopname($nick){
        return $nick . 'の店';
    }
}
