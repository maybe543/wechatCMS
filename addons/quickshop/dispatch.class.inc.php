<?php
 class Dispatch{
    private static $t_dispatch = 'quickshop_dispatch';
    public static $EXPRESS = 1;
    public static $PICKUP = 2;
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_dispatch, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_dispatch, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function get($id){
        $dispatch = pdo_fetch('select * from ' . tablename(self :: $t_dispatch) . ' where id=:id', array(':id' => $id));
        return $dispatch;
    }
    public function getUnique($weid){
        $dispatch = pdo_fetch("SELECT * FROM " . tablename(self :: $t_dispatch) . " WHERE weid = $weid  LIMIT 1");
        return $dispatch;
    }
    public function batchGet($weid, $conds = array(), $key = null){
        $condition = '';
        $dispatches = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_dispatch) . " WHERE weid = $weid  $condition ORDER BY displayorder DESC", array(), $key);
        return $dispatches;
    }
    public function remove($weid, $id){
        return pdo_query("DELETE FROM " . tablename(self :: $t_dispatch) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
    }
    public static function getSendTypeName($type){
        switch ($type){
        case self :: $PICKUP: return "自提";
        case self :: $EXPRESS: return "快递";
        default: return "送货方式未知";
        }
    }
}
