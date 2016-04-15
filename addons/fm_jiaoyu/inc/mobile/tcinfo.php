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
		$tid = intval($_GPC['tid']);
        
		//教师列表按教师入职时间先后顺序排列，先入职再前
		$leixing = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " WHERE weid = '{$_W['uniacid']}' ORDER BY id ASC, ssort DESC", array(':weid' => $_W['uniacid']), 'id');
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} AND tid ={$tid} ORDER BY end DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid, ':tid' => $tid));
        $it = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
		
		$item = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " WHERE schoolid = :schoolid And id = :id ", array(':schoolid' => $schoolid, ':id' => $tid));
       
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
        if (empty($item)) {
            message('参数错误！');
        }
		
        include $this->template('tcinfo');
?>