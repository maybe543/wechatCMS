<?php
 class Commission{
    static $t_goods = 'quickshop_goods';
    static $t_commission = 'quickdist_commission';
    function __construct(){
    }
    public function batchGetCommissionByGoodsIds($weid, $goodsids, $key = null){
        $com = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_goods) . " WHERE weid = :weid AND id IN ('" . join("','", $goodsids) . "')", array(':weid' => $weid), $key);
        return $com;
    }
    public function batchGet($weid, $conds, $pindex, $psize){
        $condition = '';
        if (isset($conds['from_user'])){
            $condition .= " AND order_leader = '" . $conds['from_user'] . "' ";
        }
        $commission = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_commission) . " WHERE weid = $weid $condition ORDER BY createtime DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_commission) . " WHERE weid = $weid $condition");
        return array($commission, $total);
    }
    public function giveCommission($weid, $orderid, $goodsid, $order_leader, $order_openid, $order_createtime, $price, $rate, $total, $com_level, $com_val, $credit_type){
        $data = array('weid' => $weid, 'orderid' => $orderid, 'goodsid' => $goodsid, 'order_leader' => $order_leader, 'order_openid' => $order_openid, 'order_createtime' => $order_createtime, 'ordergoodsprice' => $price, 'rate' => $rate, 'total' => $total, 'level' => $com_level, 'commission_value' => $com_val, 'createtime' => TIMESTAMP);
        $c = pdo_fetch("SELECT * FROM " . tablename(self :: $t_commission) . " WHERE weid=:weid AND orderid=:orderid AND goodsid=:goodsid AND order_leader=:order_leader LIMIT 1", array(':weid' => $weid, ':orderid' => $orderid, ':goodsid' => $goodsid, ':order_leader' => $order_leader));
        if (empty($c)){
            pdo_insert(self :: $t_commission, $data);
            yload() -> classs('quickcenter', 'fans');
            $_fans = new Fans();
            if ($credit_type == 2){
                $_fans -> addCredit($weid, $order_leader, $com_val, 2, $com_level . "级分销返余额");
            }else{
                $_fans -> addCredit($weid, $order_leader, $com_val * 100, 1, $com_level . "级分销返积分,1分钱=1积分");
            }
        }
    }
}
