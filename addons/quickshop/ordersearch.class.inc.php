<?php
 class OrderSearch{
    private static $t_goods = 'quickshop_goods';
    private static $t_order = 'quickshop_order';
    private static $t_order_goods = 'quickshop_order_goods';
    private static $t_sys_fans = 'fans';
    public function verifyById($weid, $orderid, $cond){
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        yload() -> classs('quickshop', 'address');
        $_address = new Address();
        $order = pdo_fetch("SELECT * FROM " . tablename(self :: $t_order) . " WHERE weid = :weid AND id = :orderid LIMIT 1", array(':weid' => $weid, 'orderid' => $orderid));
        $address = $_address -> get($order['addressid']);
        if (isset($conds['mobile'])){
            if ($address['mobile'] == $conds['mobile']){
                return false;
            }
        }
        if (isset($conds['ordersn'])){
            if ($address['ordersn'] == $conds['ordersn']){
                return false;
            }
        }
        $orderid = $order['id'];
        $status = $order['status'];
        $goodstitle = '';
        $goods = $_order -> getDetailedGoods($orderid);
        foreach($goods as $g){
            $goodstitle .= $g['title'] . 'x' . $g['total'] . ' ';
        }
        return array(1, $order, $goodstitle, $status);
    }
    public function verify($weid, $conds){
        yload() -> classs('quickshop', 'order');
        yload() -> classs('quickshop', 'goods');
        $_order = new Order();
        $conds = array_merge($conds, array('status' => Order :: $ORDER_PAYED, 'goodstype' => Goods :: $VIRTUAL_GOODS));
        $condition = '';
        if (!empty($conds['goodstype'])){
            $condition .= " AND goodstype = " . intval($conds['goodstype']);
        }
        if (!empty($conds['ordersn'])){
            $condition .= " AND ordersn = '" . $conds['ordersn'] . "'";
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition");
        if (!empty($list)){
            foreach ($list as & $row){
                !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
            }
            unset($row);
        }
        if (!empty($addressids)){
            yload() -> classs('quickshop', 'address');
            $_address = new Address();
            $addresses = $_address -> batchGetByIds($_W['weid'], $addressids, 'id');
        }
        $orders = array();
        if (isset($conds['mobile'])){
            foreach ($list as $row){
                if ($addresses[$row['addressid']]['mobile'] == $conds['mobile']) $orders[] = $row;
            }
        }else{
            $orders = $list;
        }
        $orderCount = count($orders);
        if ($orderCount == 1){
            $order = $orders[0];
            $orderid = $order['id'];
            $status = $order['status'];
            $goodstitle = '';
            $goods = $_order -> getDetailedGoods($orderid);
            foreach($goods as $g){
                $goodstitle .= $g['title'] . 'x' . $g['total'] . ' ';
            }
        }
        return array($orderCount, $order, $goodstitle, $status);
    }
    public function search($weid, $conds = array(), $key = null, $pindex, $psize){
        $condition = '';
        if (isset($conds['from_user'])){
            $condition .= " AND from_user = '" . $conds['from_user'] . "' ";
        }
        if (!empty($conds['status'])){
            $condition .= " AND status = " . intval($conds['status']);
        }
        if (!empty($conds['goodstype'])){
            $condition .= " AND goodstype = " . intval($conds['goodstype']);
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        if (!empty($list)){
            foreach ($list as & $row){
                !empty($row['addressid']) && $addressids[$row['addressid']] = $row['addressid'];
            }
            unset($row);
        }
        if (!empty($addressids)){
            yload() -> classs('quickshop', 'address');
            $_address = new Address();
            $addresses = $_address -> batchGetByIds($_W['weid'], $addressids, 'id');
        }
        $orders = array();
        foreach ($list as $row){
            if (isset($conds['ordersn'])){
                if (FALSE !== strpos($row['ordersn'], $conds['ordersn'], 0)) $orders[] = $row;
            }else if (isset($conds['realname'])){
                if (FALSE !== strpos($addresses[$row['addressid']]['realname'], $conds['realname'], 0)) $orders[] = $row;
            }else if (isset($conds['province'])){
                if (FALSE !== strpos($addresses[$row['addressid']]['province'], $conds['province'], 0)) $orders[] = $row;
            }else if (isset($conds['city'])){
                if (FALSE !== strpos($addresses[$row['addressid']]['city'], $conds['city'], 0)) $orders[] = $row;
            }else if (isset($conds['mobile'])){
                if (FALSE !== strpos($addresses[$row['addressid']]['mobile'], $conds['mobile'], 0)) $orders[] = $row;
            }
        }
        return array($orders, count($orders));
    }
}
