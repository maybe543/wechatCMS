<?php
 class Cart{
    private static $t_cart = 'quickshop_cart';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_cart, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function get($id){
        $cart = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_cart) . ' WHERE id=:id', array(':id' => $id));
        return $cart;
    }
    public function getByGoodsId($weid, $openid, $goodsid){
        $cart = pdo_fetch("SELECT * FROM " . tablename('quickshop_cart') . " WHERE from_user = :from_user AND weid = :weid AND goodsid = :goodsid", array(':from_user' => $openid, ':weid' => $weid, ':goodsid' => $goodsid));
        return $cart;
    }
    public function batchGet($weid, $openid){
        $list = pdo_fetchall("SELECT * FROM " . tablename('quickshop_cart') . " WHERE  weid = '{$weid}' AND from_user = '{$openid}'");
        return $list;
    }
    public function total($weid, $openid){
        $cartotal = pdo_fetchcolumn("select sum(total) from " . tablename('quickshop_cart') . " where weid = :weid AND from_user=:f", array(':weid' => $weid, ':f' => $openid));
        return $cartotal;
    }
    public function clear($weid, $openid){
        pdo_delete('quickshop_cart', array('from_user' => $openid, 'weid' => $weid));
    }
    public function remove($weid, $openid, $id){
        pdo_delete('quickshop_cart', array('from_user' => $openid, 'weid' => $weid, 'id' => $id));
    }
    public function update($weid, $openid, $id, $newamount){
        pdo_update('quickshop_cart', array('total' => $newamount), array('weid' => $weid, 'from_user' => $openid, 'id' => $id));
    }
}
