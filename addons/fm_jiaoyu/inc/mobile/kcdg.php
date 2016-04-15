<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
        $id = intval($_GPC['id']);

        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " where weid = :weid AND id=:id", array(':weid' => $weid, ':id' => $id));

        if (empty($item)) {
            message('参数错误');
        }
        include $this->template('kcdg');
?>