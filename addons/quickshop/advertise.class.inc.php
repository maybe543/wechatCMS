<?php
 class Advertise{
    private static $t_advertise = 'quickshop_adv';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_advertise, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_advertise, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function get($id){
        $advertise = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_advertise) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
        return $advertise;
    }
    public function batchGet($weid, $conds = array(), $key = null){
        if (isset($conds['display']) && $conds['display'] == 'all'){
        }else{
            $condition = ' AND enabled=1';
        }
        $advertises = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_advertise) . " WHERE weid = $weid  {$condition} ORDER BY displayorder ASC", array(), $key);
        return $advertises;
    }
    public function remove($weid, $id){
        return pdo_query("DELETE FROM " . tablename(self :: $t_advertise) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
    }
}
