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
		$openid = $_W['openid'];
        $studentsid = intval($_GPC['sid']);
		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	
		
        if(!empty($userid['id'])){
            
			$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $it['sid']));
			$item = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid=:uid ORDER BY uid DESC", array(':uid' => $it['uid'], ':uniacid' => $_W ['uniacid'])); 
       		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
       
	        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                  }
               }
            } 
		
		 include $this->template('myinfo');
          }else{
         include $this->template('bangding');
          }        
?>