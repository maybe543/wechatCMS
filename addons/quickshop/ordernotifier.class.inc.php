<?php
 class OrderNotifier{
    public function notifyNewOrder($param){
    }
    public function notifyQR($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify qr', $order);
        if (1){
            yload() -> classs('quickcenter', 'wechatsetting');
            $_settings = new WechatSetting();
            $setting = $_settings -> get($weid, 'quicklink');
            if (!empty($setting['autoreply_rid'])){
                yload() -> classs('quicklink', 'channelreply');
                $_channel_reply = new ChannelReply();
                $channel_reply = $_channel_reply -> getKeywordAndChannel($weid, $setting['autoreply_rid']);
                if (!empty($channel_reply)){
                    yload() -> classs('quickcenter', 'wechatutil');
                    $url = WechatUtil :: createMobileUrl('RunTask', 'quicklink', array('from_user' => $order['from_user'], 'channel_id' => $channel_reply['channel'], 'rule' => $channel_reply['content']));
                    $ret = WechatUtil :: fsock_http_request($url, 30);
                    WeUtility :: logging("Running task", $url . "==>" . json_encode($ret));
                }
            }
        }
        WeUtility :: logging('notify payed done');
    }
    public function notifyPayed($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify payed go', $order);
        $first = '下单成功';
        if ($order['goodstype'] == Goods :: $VIRTUAL_GOODS){
            $remark = '感谢您的支持！请妥善保管订单编号，它是您的付款凭证。';
        }else{
            $remark = '感谢您的支持！我们将尽快安排发货。';
        }
        $root = str_replace('payment/wechat/', '', $_W['siteroot']);
        $url = $root . '/app/' . murl('entry/module/myorder', array('weid' => $order['weid'], 'm' => 'quickshop'));
        if (!empty($template_id)){
            $data = array('touser' => $order['from_user'], 'url' => $url, 'template_id' => $template_id, 'url' => $url, 'topcolor' => '#ff0000', 'data' => array('first' => array('value' => $first, 'color' => '#173177'), 'OrderSn' => array('value' => $order['ordersn'], 'color' => '#173177'), 'OrderStatus' => array('value' => $_order -> getOrderStatusName($order['status'], $order['goodstype']), 'color' => '#173177'), 'remark' => array('value' => $remark, 'color' => '#173177')),);
            $_weapi -> sendTemplateMsg($data);
        }else{
            $_weapi -> sendText($order['from_user'], "订单号为<a href='{$url}'>" . $order['ordersn'] . '</a>的订单下单成功。' . $remark);
        }
        if (1){
            yload() -> classs('quickdist', 'memberorder');
            $_distnotifier = new MemberOrder();
            $_distnotifier -> notifyCommission($order);
        }
        if (1){
            yload() -> classs('quickdist', 'memberorder');
            $_distnotifier = new MemberOrder();
            $_distnotifier -> notifyDealer($order);
        }
        WeUtility :: logging('notify payed done');
    }
    public function notifyDelivered($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify payed go', $order);
        if ($order['goodstype'] == Goods :: $VIRTUAL_GOODS){
            $first = '确认通知';
            $remark = '后台已经确认了您的订单。请妥善保管订单编号，它是您的付款凭证。';
        }else{
            $first = '发货通知';
            if (!empty($order['expresssn'])){
                $express_info = '【' . $order['expresscom'] . '】快递号:' . $order['expresssn'];
            }
            $remark = '您好，您购买的商品已经发货，感谢您的支持。' . $express_info;
        }
        $url = $_W['siteroot'] . 'app/' . murl('entry/module/myorder', array('weid' => $order['weid'], 'm' => 'quickshop'));
        if (!empty($template_id)){
            $data = array('touser' => $order['from_user'], 'url' => $url, 'template_id' => $template_id, 'topcolor' => '#ff0000', 'data' => array('first' => array('value' => $first, 'color' => '#173177'), 'OrderSn' => array('value' => $order['ordersn'], 'color' => '#173177'), 'OrderStatus' => array('value' => $_order -> getOrderStatusName($order['status'], $order['goodstype']), 'color' => '#173177'), 'remark' => array('value' => $remark, 'color' => '#173177')),);
            $_weapi -> sendTemplateMsg($data);
        }else{
            $_weapi -> sendText($order['from_user'], $remark . "<a href='{$url}'>查看详情</a>");
        }
        WeUtility :: logging('notify payed done');
    }
    public function notifyReceived($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify payed go', $order);
        if ($order['goodstype'] == Goods :: $VIRTUAL_GOODS){
            $first = '状态通知';
            $remark = '您好，感谢您的支持。后继任何疑问，请与我们的客服联系。谢谢！';
        }else{
            $first = '订单已收货';
            $remark = '您好，感谢您的支持。后继商品如有问题，请与我们的客服联系。谢谢！';
        }
        $url = $_W['siteroot'] . 'app/' . murl('entry/module/myorder', array('weid' => $order['weid'], 'm' => 'quickshop'));
        if (!empty($template_id)){
            $data = array('touser' => $order['from_user'], 'url' => $url, 'template_id' => $template_id, 'topcolor' => '#ff0000', 'data' => array('first' => array('value' => $first, 'color' => '#173177'), 'OrderSn' => array('value' => $order['ordersn'], 'color' => '#173177'), 'OrderStatus' => array('value' => $_order -> getOrderStatusName($order['status'], $order['goodstype']), 'color' => '#173177'), 'remark' => array('value' => $remark, 'color' => '#173177')),);
            $_weapi -> sendTemplateMsg($data);
        }else{
            $_weapi -> sendText($order['from_user'], $remark . "<a href='{$url}'>查看详情</a>");
        }
        WeUtility :: logging('notify recieved done');
    }
    public function notifyAdminConfirmed($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify payed go', $order);
        if (!empty($order['expresssn'])){
            $express_info = '【' . $order['expresscom'] . '】快递号:' . $order['expresssn'];
        }
        if ($order['goodstype'] == Goods :: $VIRTUAL_GOODS){
            $first = '状态通知';
            $remark = '您好，感谢您的支持。后继任何疑问，请与我们的客服联系。谢谢！';
        }else{
            $first = '订单已收货';
            $remark = '您好，快递显示您的商品已经收货，感谢您的支持。后继商品如有问题，请与我们的客服联系。谢谢！' . $express_info;
        }
        $url = $_W['siteroot'] . 'app/' . murl('entry/module/myorder', array('weid' => $order['weid'], 'm' => 'quickshop'));
        if (!empty($template_id)){
            $data = array('touser' => $order['from_user'], 'url' => $url, 'template_id' => $template_id, 'topcolor' => '#ff0000', 'data' => array('first' => array('value' => $first, 'color' => '#173177'), 'OrderSn' => array('value' => $order['ordersn'], 'color' => '#173177'), 'OrderStatus' => array('value' => $_order -> getOrderStatusName($order['status'], $order['goodstype']), 'color' => '#173177'), 'remark' => array('value' => $remark, 'color' => '#173177')),);
            $_weapi -> sendTemplateMsg($data);
        }else{
            $_weapi -> sendText($order['from_user'], $remark);
        }
        WeUtility :: logging('notify recieved done');
    }
    public function notifyCancel($param){
        global $_W;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $_weapi = new WechatAPI();
        extract($param);
        $order = $_order -> get($orderid);
        WeUtility :: logging('notify payed go', $order);
        $url = $_W['siteroot'] . 'app/' . murl('entry/module/myorder', array('weid' => $order['weid'], 'm' => 'quickshop'));
        if (!empty($template_id)){
            $data = array('touser' => $order['from_user'], 'url' => $url, 'template_id' => $template_id, 'topcolor' => '#ff0000', 'data' => array('first' => array('value' => '订单已取消', 'color' => '#173177'), 'OrderSn' => array('value' => $order['ordersn'], 'color' => '#173177'), 'OrderStatus' => array('value' => $_order -> getOrderStatusName($order['status'], $order['goodstype']), 'color' => '#173177'), 'remark' => array('value' => '您好，您的订单已被后台小二取消，感谢您的支持。', 'color' => '#173177')),);
            $_weapi -> sendTemplateMsg($data);
        }else{
            $_weapi -> sendText($order['from_user'], "您的订单编号为<a href='{$url}'>" . $order['ordersn'] . '</a>的订单已被后台小二取消，感谢您的支持。');
        }
        WeUtility :: logging('notify recieved done');
    }
}
