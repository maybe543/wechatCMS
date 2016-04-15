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
        $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_cook) . " WHERE weid = '{$_W['uniacid']}' AND schoolid ={$schoolid} AND ishow = 1 ORDER BY sort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid));
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_cook) . " WHERE schoolid = :schoolid ", array(':schoolid' => $schoolid));
        $school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $_W['uniacid'], ':id' => $schoolid));
		$monarr = iunserializer($item['monday']);//取周一早餐图片
				
        include $this->template('cooklist');
?>