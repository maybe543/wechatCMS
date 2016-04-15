<?php
 class Goods{
    private static $t_goods = 'quickshop_goods';
    private static $t_order = 'quickshop_order';
    private static $t_order_goods = 'quickshop_order_goods';
    public static $PHYSICAL_GOODS = 1;
    public static $VIRTUAL_GOODS = 2;
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_goods, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function fork($pid){
        if (!empty($pid)){
            $child = $this -> get($pid);
            $child['id'] = null;
            $child['title'] = $child['title'] . '-子规格';
            $child['pgoodsid'] = $pid;
            $id = -1;
            $ret = pdo_insert(self :: $t_goods, $child);
            if (false !== $ret){
                $id = pdo_insertid();
            }
        }else{
            $id = null;
        }
        return $id;
    }
    public function get($id){
        $goods = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_goods) . ' WHERE id=:id', array(':id' => $id));
        return $goods;
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_goods, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function hasBuy($weid, $from_user, $goodsid){
        $flag = pdo_fetch("SELECT a.id FROM " . tablename(self :: $t_order) . ' a LEFT JOIN ' . tablename(self :: $t_order_goods) . ' b ON a.id = b.orderid AND a.weid=b.weid
      WHERE a.from_user=:from_user AND a.weid=:weid AND b.goodsid=:goodsid AND b.weid=:weid AND a.status >= 3 AND a.status <= 6 LIMIT 1', array(':from_user' => $from_user, ':goodsid' => $goodsid, ':weid' => $weid));
        return $flag;
    }
    public function updateViewCount($weid, $id){
        return pdo_query("update " . tablename(self :: $t_goods) . " set viewcount=viewcount+1 where id=:id and weid=:weid", array(':weid' => $weid, ':id' => $id));
    }
    public function markDelete($weid, $id){
        $ret = pdo_update(self :: $t_goods, array("deleted" => 1), array('id' => $id, 'weid' => $weid));
        return $ret;
    }
    public function batchGetSubSpec($weid, $goodsid){
        return $this -> batchGet($weid, array('pgoodsid' => $goodsid));
    }
    public function batchGet($weid, $conds = array(), $pindex = 1, $psize = 100000, $key = null){
        $condition = '';
        if (!empty($conds['keyword'])){
            $condition .= " AND title LIKE '%{$conds['keyword']}%'";
        }
        if (!empty($conds['cate_2'])){
            $cid = intval($conds['cate_2']);
            $condition .= " AND ccate = '{$cid}'";
        }elseif (!empty($conds['cate_1'])){
            $cid = intval($conds['cate_1']);
            $condition .= " AND pcate = '{$cid}' ";
        }
        if (isset($conds['pgoodsid'])){
            $condition .= " AND pgoodsid = '" . intval($conds['pgoodsid']) . "'";
        }else{
            $condition .= " AND pgoodsid = 0";
        }
        if (isset($conds['status'])){
            $condition .= " AND status = '" . intval($conds['status']) . "'";
        }
        if (isset($conds['isnew'])){
            $condition .= " AND isnew = " . intval($conds['isnew']) ;
        }
        if (isset($conds['ishot'])){
            $condition .= " AND ishot = " . intval($conds['ishot']) ;
        }
        if (isset($conds['isdiscount'])){
            $condition .= " AND isdiscount = " . intval($conds['isdiscount']) ;
        }
        if (isset($conds['isrecommend'])){
            $condition .= " AND isrecommend = " . intval($conds['isrecommend']) ;
        }
        if (isset($conds['istime'])){
            $condition .= " AND istime = " . intval($conds['istime']) ;
        }
        if (isset($conds['min_visible_level'])){
            $condition .= " AND min_visible_level <= " . intval($conds['min_visible_level']) ;
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_goods) . " WHERE weid = $weid and deleted=0 $condition ORDER BY status DESC, pcate DESC, displayorder DESC, id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('quickshop_goods') . " WHERE weid = $weid  and deleted=0 $condition");
        return array($list, $total);
    }
    public function batchGetCount($weid, $conds = array()){
        $condition = '';
        if (!empty($conds['keyword'])){
            $condition .= " AND title LIKE '%{$conds['keyword']}%'";
        }
        if (!empty($conds['cate_2'])){
            $cid = intval($conds['cate_2']);
            $condition .= " AND ccate = '{$cid}'";
        }elseif (!empty($conds['cate_1'])){
            $cid = intval($conds['cate_1']);
            $condition .= " AND pcate = '{$cid}' ";
        }
        if (isset($conds['pgoodsid'])){
            $condition .= " AND pgoodsid = '" . intval($conds['pgoodsid']) . "'";
        }else{
            $condition .= " AND pgoodsid = 0";
        }
        if (isset($conds['status'])){
            $condition .= " AND status = '" . intval($conds['status']) . "'";
        }
        if (isset($conds['isrecommend'])){
            $condition .= " AND isrecommend = " . intval($conds['isrecommend']) ;
        }
        if (isset($conds['isnew'])){
            $condition .= " AND isnew = " . intval($conds['isnew']) ;
        }
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('quickshop_goods') . " WHERE weid = $weid  and deleted=0 $condition");
        return $total;
    }
    public function batchGetByPrimaryCategory($weid, $pcate, $other_cond, $pindex = 1, $psize = 100000){
        $conds = array_merge(array('cate_1' => $pcate, 'status' => 1), $other_cond);
        return $this -> batchGet($weid, $conds, $pindex, $psize);
    }
    public function batchGetByRecommend($weid, $other_cond, $pindex = 1, $psize = 100000){
        $conds = array_merge(array('isrecommend' => 1, 'status' => 1), $other_cond);
        return $this -> batchGet($weid, $conds, $pindex, $psize);
    }
    public function batchGetByHot($weid, $other_cond, $pindex = 1, $psize = 100000){
        $conds = array_merge(array('ishot' => 1, 'status' => 1), $other_cond);
        return $this -> batchGet($weid, $conds, $pindex, $psize);
    }
    public function batchGetByIds($weid, $ids, $key = null){
        $goods = array();
        if (!empty($ids) and count($ids) > 0 and is_array($ids)){
            $goods = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_goods) . " WHERE id IN ('" . implode("','", $ids) . "')", array(), $key);
        }
        return $goods;
    }
}
