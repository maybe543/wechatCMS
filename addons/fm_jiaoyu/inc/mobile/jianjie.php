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
		$schoolid = intval($_GPC['schoolid']);

        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $id));
        $title = $item['title'];

        if (empty($item)) {
            message('参数错误');
        }
        include $this->template('jianjie');
?>