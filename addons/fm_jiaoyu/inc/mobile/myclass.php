<?php
/**
 * 微教育模块
 *
 * @author 高贵血迹
 */
        global $_W, $_GPC;
        $weid = $this->weid;
        $from_user = $this->_fromuser;
		$schoolid = intval($_GPC['schoolid']);
		$bj_id = intval($_GPC['bj_id']);
		$xq_id = intval($_GPC['xq_id']);
        
		//教师列表按教师入职时间先后顺序排列，先入职再前

        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = :weid AND schoolid =:schoolid AND bj_id = :bj_id ORDER BY end DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid, ':bj_id' => $bj_id));
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE schoolid = :schoolid ", array(':schoolid' => $schoolid));
        $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid =:schoolid ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
        $teachers = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " WHERE weid = :weid AND schoolid =:schoolid", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'id');
        if (!empty($teachers)) {
            $child = '';
            foreach ($teachers as $pid => $pcate) {
                if (!empty($pcate['parentid'])) {
                    $child[$pcate['parentid']][$pcate['id']] = array($pcate['id'], $pcate['name']);
                }
            }
        }
		
        include $this->template('myclass');
?>