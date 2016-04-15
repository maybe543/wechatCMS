<?php
 class Category{
    private static $t_category = 'quickshop_category';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_category, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_category, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function get($id){
        $category = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_category) . ' WHERE id=:id', array(':id' => $id));
        return $category;
    }
    public function batchGet($weid, $conds = array(), $key = null){
        $condition = '';
        if (isset($conds['isrecommend'])){
            $condition .= " AND isrecommend = " . intval($conds['isrecommend']) ;
        }
        if (isset($conds['enabled'])){
            $condition .= " AND enabled = " . intval($conds['enabled']) ;
        }
        $categories = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_category) . " WHERE weid = $weid  $condition ORDER BY parentid ASC, displayorder DESC", array(), $key);
        return $categories;
    }
    public function remove($weid, $id){
        return pdo_query("DELETE FROM " . tablename(self :: $t_category) . " WHERE (id=:id) AND weid=:weid", array(':weid' => $weid, ':id' => $id));
    }
}
