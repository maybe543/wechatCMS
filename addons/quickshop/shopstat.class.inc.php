<?php
 class ShopStat{
    private static $t_goods = 'quickshop_goods';
    public function getAllGoodsViewCount($weid){
        $data = pdo_fetchall("SELECT title, id, viewcount FROM " . tablename(self :: $t_goods) . " WHERE weid={$weid} AND deleted=0 ORDER BY viewcount DESC");
        return $data;
    }
}
