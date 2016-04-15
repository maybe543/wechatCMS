<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $kwd = $_GPC['keyword'];
        $sql = 'SELECT * FROM ' . tablename($this->table_index) . ' WHERE `weid`=:weid AND `title` LIKE :title';
        $params = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r = array();
            $r['title'] = $row['title'];
            $r['description'] = $row['info'];
            $r['thumb'] = tomedia($row['logo']);
            $r['mid'] = $row['id'];
            $row['entry'] = $r;
        }
        include $this->template('web/query');
?>