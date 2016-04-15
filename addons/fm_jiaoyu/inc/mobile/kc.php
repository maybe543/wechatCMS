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
		$leixing = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " WHERE weid = '{$_W['uniacid']}' ORDER BY id ASC, ssort DESC", array(':weid' => $_W['uniacid']), 'id');
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid}  ORDER BY end DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid));
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_tcourse) . " WHERE id = :id ", array(':id' => $id));
        $title = $item['title'];
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		$km = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " where weid = '{$_W['uniacid']}' AND schoolid =$schoolid And type = 'subject' ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':type' => 'subject', ':schoolid' => $schoolid));
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_classify) . " WHERE sid = :sid", array(':sid' => $sid));
        if (empty($schoolid)) {
            message('没有找到该学校，请联系管理员！');
        }
		
        include $this->template('kc');
?>