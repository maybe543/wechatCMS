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
        
        //查询是否用户登录		
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, 'tid' => 0), 'id');
		
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	
		
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		
		$student = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $it['sid']));		

		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid = :weid AND schoolid = :schoolid ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        		
        if(!empty($userid['id'])){
			
			$teacher = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid ORDER BY id DESC", array(':weid' => $_W ['uniacid']), 'id');
						
			$leave = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " where :schoolid = schoolid And :weid = weid And :type = type And :bj_id = bj_id ORDER BY createtime DESC", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $student['bj_id'],
				 ':type' => 3
				 ));
				 
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE id = :id ", array(':id' => $id));	
						
		 include $this->template('szuoyelist');
          }else{
         include $this->template('bangding');
          }        
?>