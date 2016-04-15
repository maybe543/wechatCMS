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
        
	    $s_name = trim($_GPC['s_name']);
        $mobile = trim($_GPC['mobile']);
		
        $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
		
        $students = pdo_fetchall("SELECT * FROM " . tablename($this->table_students) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY id ASC, id DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'id');
        if (!empty($students)) {
            $child = '';
            foreach ($students as $pid => $pcate) {
                if (!empty($pcate['parentid'])) {
                    $child[$pcate['parentid']][$pcate['id']] = array($pcate['id'], $pcate['name']);
                }
            }
        }
		
		$sid = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where :schoolid = schoolid And :weid = weid And :s_name = s_name And :mobile = mobile", array(':weid' => $weid, ':schoolid' => $schoolid, ':mobile'=>$mobile, ':s_name'=>$s_name), 'id');
        if(!empty($sid['id'])){
			$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_score) . " where schoolid = :schoolid And weid = :weid And sid = :sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':sid'=>$sid['id']));
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_score) . " WHERE id = :id", array(':id' => $id));
        
		
		 include $this->template('chaxun');
          }else{
          include $this->template('404');
          }	
       
?>