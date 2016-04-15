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
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :sid = sid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, 'sid' => 0), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	
		$school = pdo_fetch("SELECT * FROM " . tablename($this->table_index) . " where weid = :weid AND id=:id ORDER BY ssort DESC", array(':weid' => $weid, ':id' => $schoolid));
		$teachers = pdo_fetch("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid AND id = :id", array(':weid' => $_W ['uniacid'], ':id' => $it['tid']));		
		
		
		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE weid =  '{$_W['uniacid']}' AND schoolid ={$schoolid} ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
        if (!empty($category)) {
            $children = '';
            foreach ($category as $cid => $cate) {
                if (!empty($cate['parentid'])) {
                    $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
                }
            }
        }
        
        $bzrtid1 = $category[$teachers['bj_id1']]['tid'];
		$bzrtid2 = $category[$teachers['bj_id2']]['tid'];
		$bzrtid3 = $category[$teachers['bj_id3']]['tid'];
		
        if(!empty($userid['id'])){
			
			$teacher = pdo_fetchall("SELECT * FROM " . tablename($this->table_teachers) . " where weid = :weid ORDER BY id DESC", array(':weid' => $_W ['uniacid']), 'id');
			
			$member = pdo_fetchall("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid ORDER BY uid ASC", array(':uniacid' => $_W ['uniacid']), 'uid');
			
		    $leave1 = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " where :schoolid = schoolid And :weid = weid And :tid = tid And :bj_id = bj_id And :type = type ORDER BY createtime DESC", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $teachers['bj_id1'],
				 ':type' => 1,
				 ':tid' => $teachers['id']
				 ));
		    $leave2 = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " where :schoolid = schoolid And :weid = weid And :tid = tid And :bj_id = bj_id And :type = type ORDER BY createtime DESC", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $teachers['bj_id2'],
				 ':type' => 1,
				 ':tid' => $teachers['id']
				 ));
		    $leave3 = pdo_fetchall("SELECT * FROM " . tablename($this->table_notice) . " where :schoolid = schoolid And :weid = weid And :tid = tid And :bj_id = bj_id And :type = type ORDER BY createtime DESC", array(
		         ':weid' => $weid,
				 ':schoolid' => $schoolid,
				 ':bj_id' => $teachers['bj_id3'],
				 ':type' => 1,
				 ':tid' => $teachers['id']
				 ));				 
            $item = pdo_fetch("SELECT * FROM " . tablename($this->table_notice) . " WHERE id = :id ", array(':id' => $id));	
						
		 include $this->template('noticelist');
          }else{
         include $this->template('bangding');
          }        
?>