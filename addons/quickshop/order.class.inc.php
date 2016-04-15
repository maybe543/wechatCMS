<?php
 class Order{
    public static $ORDER_CANCEL = 1;
    public static $ORDER_NEW = 2;
    public static $ORDER_PAYED = 3;
    public static $ORDER_DELIVERED = 4;
    public static $ORDER_RECEIVED = 5;
    public static $ORDER_CONFIRMED = 6;
    public static $ORDER_FAIL = 7;
    public static $PAY_ONLINE = 2;
    public static $PAY_DELIVERY = 3;
    public static $PAY_CREDIT = 4;
    private static $t_goods = 'quickshop_goods';
    private static $t_order = 'quickshop_order';
    private static $t_order_goods = 'quickshop_order_goods';
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_members = 'mc_members';
    private static $t_sys_member = 'mc_members';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_order, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function update($weid, $id, $data){
        if (isset($data['status'])){
            $data['updatetime'] = TIMESTAMP;
        }
        $ret = pdo_update(self :: $t_order, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function clientUpdate($weid, $from_user, $id, $data){
        if (isset($data['status'])){
            $data['updatetime'] = TIMESTAMP;
        }
        $ret = pdo_update(self :: $t_order, $data, array('weid' => $weid, 'from_user' => $from_user, 'id' => $id));
        return $ret;
    }
    public function get($id){
        $order = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_order) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
        return $order;
    }
    public function clientGet($weid, $from_user, $id){
        $order = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_order) . ' WHERE weid=:weid AND id=:id AND from_user=:f LIMIT 1', array(':weid' => $weid, ':id' => $id, ':f' => $from_user));
        return $order;
    }
    public function batchGet($weid, $conds = array(), $key = null, $pindex, $psize){
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
        if (!empty($conds['sendtype'])){
            $condition .= " AND sendtype = " . intval($conds['sendtype']);
        }
        $orders = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition");
        return array($orders, $total);
    }
    public function batchGetById($weid, $ids = array(), $key = null, $pindex, $psize){
        $condition = 'AND id IN (-1';
        if (count($ids) <= 0){
            return array(null, 0);
        }else{
            foreach ($ids as $id){
                $condition .= "," . $id;
            }
            $condition .= ')';
        }
        $orders = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_order) . " WHERE weid = $weid $condition");
        return array($orders, $total);
    }
    public function batchGetCancelled($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_CANCEL ;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function batchGetNew($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_NEW;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function batchGetPayed($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_PAYED;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function batchGetDelivered($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_DELIVERED;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function batchGetReceived($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_RECEIVED;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function batchGetSuccess($weid, $conds = array(), $key = null, $pindex, $psize){
        $conds['status'] = self :: $ORDER_CONFIRMED;
        return $this -> batchGet($weid, $conds, $key, $pindex, $psize);
    }
    public function remove($weid, $id){
        pdo_delete(self :: $t_order, array('weid' => $weid, 'id' => $id));
        $ret = pdo_delete(self :: $t_order_goods, array('weid' => $weid, 'orderid' => $id));
        return $ret;
    }
    public function addGoods($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_order_goods, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function getGoods($orderid, $key = null){
        $goods = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order_goods) . " WHERE orderid = $orderid", array(), $key);
        return $goods;
    }
    public function getDetailedGoods($orderid, $key = null){
        $goods = pdo_fetchall("SELECT g.support_delivery, g.goodstype, g.maxbuy, g.id, g.totalcnf, g.status, g.sales, g.title, g.thumb, g.unit, o.ordergoodsprice marketprice,g.productprice, g.costprice, g.total goodstotal, o.total,o.optionid,o.total ordertotal, o.optionname, o.ordergoodsprice FROM " . tablename(self :: $t_order_goods) . " o " . " LEFT JOIN " . tablename(self :: $t_goods) . " g " . " ON o.goodsid=g.id " . " WHERE o.orderid='{$orderid}'");
        return $goods;
    }
    public function batchGetByOpenIds($weid, $openids, $conds = array(), $key = null, $pindex = 1, $psize = 9999999){
        $condition = '';
        if (empty($openids)){
            return array(array(), 0);
        }
        $condition .= " AND a.from_user IN ('" . join("','", $openids) . "')";
        if (isset($conds['status']) and $conds['allstatus'] = 0){
            $condition .= " AND a.status = " . intval($conds['status']);
        }
        $orders = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_order) . " a " . " LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.weid=b.uniacid AND a.from_user=b.openid " . " LEFT JOIN " . tablename(self :: $t_sys_members) . " c ON b.uid = c.uid " . " WHERE a.weid = $weid $condition ORDER BY a.id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_order) . " a " . " WHERE weid = $weid $condition");
        return array($orders, $total);
    }
    public function batchGetOrderGoodsByOpenIds($weid, $openids, $conds = array(), $key = null, $pindex = 1, $psize = 9999999){
        $condition = '';
        if ((!is_array($openids)) or empty($openids) or count($openids) <= 0){
            return array(array(), 0);
        }
        $condition .= " AND a.from_user IN ('" . join("','", $openids) . "')";
        $sql = "SELECT c.*, b.*, a.* FROM " . tablename(self :: $t_order) . " a " . " LEFT JOIN " . tablename(self :: $t_order_goods) . " c ON a.weid=c.weid AND a.id =c.orderid " . " LEFT JOIN " . tablename(self :: $t_sys_fans) . " b ON a.weid=b.uniacid AND a.from_user=b.openid" . " WHERE a.weid = $weid $condition ORDER BY a.id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $orders = pdo_fetchall($sql, array(), $key);
        return $orders;
    }
    public static function getPayTypeLabel($paytype){
        switch($paytype){
        case self :: $PAY_ONLINE: return 'danger';
        case self :: $PAY_DELIVERY: return 'warning';
        case self :: $PAY_CREDIT: return 'info';
        default: return 'default';
        }
    }
    public static function getPayTypeName($paytype){
        switch($paytype){
        case self :: $PAY_ONLINE: return '在线支付';
        case self :: $PAY_DELIVERY: return '货到付款';
        case self :: $PAY_CREDIT: return '余额支付';
        default: return '未知';
        }
    }
    public static function getOrderStatusName($status, $goodstype = 1){
        if ($goodstype == 2){
            switch ($status){
            case self :: $ORDER_CANCEL: return '订单取消';
            case self :: $ORDER_NEW: return '待付款';
            case self :: $ORDER_PAYED: return '下单成功';
            case self :: $ORDER_DELIVERED: return '已确认';
            case self :: $ORDER_RECEIVED: return '已完成';
            case self :: $ORDER_CONFIRMED: return '已完成';
            case self :: $ORDER_FAIL: return '支付失败';
            default: return '全部';
            }
        }else{
            switch ($status){
            case self :: $ORDER_CANCEL: return '订单取消';
            case self :: $ORDER_NEW: return '待付款';
            case self :: $ORDER_PAYED: return '待发货';
            case self :: $ORDER_DELIVERED: return '待收货';
            case self :: $ORDER_RECEIVED: return '已收货';
            case self :: $ORDER_CONFIRMED: return '已完成';
            case self :: $ORDER_FAIL: return '支付失败';
            default: return '全部';
            }
        }
    }
    public function getTotalOrderedGoodsCount($weid, $from_user, $goodsid){
        $total = pdo_fetchcolumn("SELECT SUM(b.total) FROM " . tablename('quickshop_order') . " a, " . tablename('quickshop_order_goods') . " b " . " WHERE a.weid={$weid} and b.weid={$weid} AND a.from_user='{$from_user}' AND a.status >= :status AND a.id=b.orderid AND b.goodsid=:goodsid", array(':status' => self :: $ORDER_NEW, ':goodsid' => $goodsid));
        return $total;
    }
    public function getTotalBuy($weid, $from_user, $goodsid){
        $total = pdo_fetchcolumn("SELECT SUM(b.total) FROM " . tablename('quickshop_order') . " a, " . tablename('quickshop_order_goods') . " b " . " WHERE a.weid={$weid} and b.weid={$weid} AND a.from_user='{$from_user}' AND a.status >= :status AND a.id=b.orderid AND b.goodsid=:goodsid", array(':status' => self :: $ORDER_PAYED, ':goodsid' => $goodsid));
        return $total;
    }
    public function getTotalNew($weid, $from_user, $goodsid){
        $total = pdo_fetchcolumn("SELECT SUM(b.total) FROM " . tablename('quickshop_order') . " a, " . tablename('quickshop_order_goods') . " b " . " WHERE a.weid={$weid} and b.weid={$weid} AND a.from_user='{$from_user}' AND a.status = :status AND a.id=b.orderid AND b.goodsid=:goodsid", array(':status' => self :: $ORDER_NEW, ':goodsid' => $goodsid));
        return $total;
    }
    public function getAchievementByTime($weid, $status, $seconds){
        $now = TIMESTAMP;
        $status_cond = (empty($status)) ? "" : " AND status IN (" . join(',', $status) . ")";
        $total = pdo_fetchcolumn("SELECT SUM(a.price) FROM " . tablename('quickshop_order') . " a " . " WHERE a.weid={$weid} AND createtime > {$now} - {$seconds} {$status_cond}");
        return round($total, 2);
    }
    public function disappear($weid, $from_user){
        list($orders, $total) = $this -> batchGet($weid, array('from_user' => $from_user), null, 1, 1000000);
        foreach ($orders as $order){
            pdo_delete(self :: $t_order_goods, array('weid' => $weid, 'orderid' => $order['id']));
        }
        pdo_delete(self :: $t_order, array('weid' => $weid, 'from_user' => $from_user));
    }
    public function clientRemove($weid, $from_user, $id){
        pdo_delete(self :: $t_order, array('weid' => $weid, 'from_user' => $from_user, 'id' => $id));
        $ret = pdo_delete(self :: $t_order_goods, array('weid' => $weid, 'orderid' => $id));
        return $ret;
    }
}
