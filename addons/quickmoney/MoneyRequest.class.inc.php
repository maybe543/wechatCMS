<?php
 class MoneyRequest{
    private static $t_request = 'quickmoney_request';
    public function batchGetN($weid, $conds = array(), $key = null, $pindex, $psize){
        $condition = '';
        if (isset($conds['from_user'])){
            $condition .= " AND from_user = '" . $conds['from_user'] . "' ";
        }
        if (!empty($conds['status'])){
            $condition .= " AND status = '" . $conds['status'] . "' ";
        }
        $requests = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_request) . " WHERE weid = $weid $condition ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_request) . " WHERE weid = $weid $condition");
        return array($requests, $total);
    }
    public function batchGet($weid, $conds = array(), $key = null, $pindex, $psize){
        $condition = '';
        if (isset($conds['from_user'])){
            $condition .= " AND from_user = '" . $conds['from_user'] . "' ";
        }
        if (!empty($conds['status'])){
            $condition .= " AND status = '" . $conds['status'] . "' ";
        }
        $requests = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_request) . " WHERE weid = $weid $condition ORDER BY id DESC " . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
        return $requests;
    }
    public function getTotalExchanaged($weid, $conds = array()){
        $condition = '';
        if (isset($conds['from_user'])){
            $condition .= " AND from_user = '" . $conds['from_user'] . "' ";
        }
        if (!empty($conds['status'])){
            $condition .= " AND status = '" . $conds['status'] . "' ";
        }
        $totalExchanged = pdo_fetchcolumn("SELECT sum(cost) FROM " . tablename(self :: $t_request) . " WHERE weid = $weid $condition ");
        if (empty($totalExchanged)) $totalExchanged = 0;
        return $totalExchanged;
    }
    public function markAllAsExchanged($weid){
        $ret = pdo_query("UPDATE " . tablenmae(self :: $t_request) . " SET `status` = 'done' WHERE weid={$weid} AND `status`='new'");
        return $ret;
    }
    public function getStatusName($status){
        if ($status == 'done'){
            return '已完成';
        }else if ($status == 'new'){
            return '处理中';
        }
        return '未知';
    }
}
