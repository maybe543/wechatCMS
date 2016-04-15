<?php
 class OrderEmail{
    function send($weid, $order){
        xload() -> classs('goods');
        xload() -> classs('order');
        xload() -> classs('address');
        $_goods = new Goods();
        $_order = new Order();
        $_address = new Address();
        $orderid = $order['id'];
        $addressid = $order['addressid'];
        $ordergoods = $_order -> getGoods($orderid, 'goodsid');
        if (!empty($ordergoods)){
            $goods = $_goods -> batchGetByIds($weid, array_keys($ordergoods));
        }
        $address = $_address -> get($addressid);
        $body = "<h3>购买商品清单</h3> <br />";
        if (!empty($goods)){
            foreach ($goods as $row){
                $body .= "名称：{$row['title']} ，数量：{$ordergoods[$row['id']]['total']} <br />";
            }
        }
        $body .= "<br />总金额：{$order['price']}元 （已付款）<br />";
        $body .= "<h3>购买用户详情</h3> <br />";
        $body .= "真实姓名：$address['realname'] <br />";
        $body .= "地区：$address['province'] - $address['city'] - $address['area']<br />";
        $body .= "详细地址：$address['address'] <br />";
        $body .= "手机：$address['mobile'] <br />";
        ihttp_email($this -> module['config']['noticeemail'], '微商城订单提醒', $body);
    }
}
