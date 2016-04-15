<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
        $do = 'rest';
        $areaid = intval($_GPC['areaid']);
        $typeid = intval($_GPC['typeid']);
        $sortid = intval($_GPC['sortid']);
        $lat = trim($_GPC['lat']);
        $lng = trim($_GPC['lng']);

        
        if ($areaid != 0) {
            $strwhere = " AND areaid={$areaid} ";
        }

        if ($typeid != 0) {
            $strwhere .= " AND typeid={$typeid} ";
        }

        //所属区域
        $area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = :weid ORDER BY ssort DESC", array(':weid' => $weid), 'id');
        $curarea = "全部";
        if (!empty($area[$areaid]['name'])) {
            $curarea = $area[$areaid]['name'];
        }
        //学校类型
        $shoptype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY ssort DESC", array(':weid' => $weid), 'id');
        $curtype = "学校类型";
        if (!empty($shoptype[$areaid]['name'])) {
            $curtype = $shoptype[$areaid]['name'];
        }


        if ($sortid == 1) {
            $restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid and is_show=1 {$strwhere} ORDER BY is_show DESC,ssort DESC, id DESC", array(':weid' => $weid));
        } else if ($sortid == 2) {
            $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_index) . " WHERE weid = :weid and is_show=1 ORDER BY dist, ssort DESC,id DESC", array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
        } else {
            $restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid and is_show=1 {$strwhere} ORDER BY is_show DESC,ssort DESC, id DESC", array(':weid' => $weid));
        }

        include $this->template('wapindex');
?>