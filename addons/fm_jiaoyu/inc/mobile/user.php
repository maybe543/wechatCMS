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
		$userid = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where :schoolid = schoolid And :weid = weid And :openid = openid And :tid = tid", array(':weid' => $weid, ':schoolid' => $schoolid, ':openid' => $openid, 'tid' => '0'), 'id');
		$it = pdo_fetch("SELECT * FROM " . tablename($this->table_user) . " where weid = :weid AND id=:id ORDER BY id DESC", array(':weid' => $weid, ':id' => $userid['id']));	

		$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_classify) . " WHERE :schoolid = schoolid And :weid = weid ORDER BY sid ASC, ssort DESC", array(':weid' => $_W['uniacid'], ':schoolid' => $schoolid), 'sid');
		
        if(!empty($userid['id']) && $userid['tid'] == '0'){
            
			$students = pdo_fetch("SELECT * FROM " . tablename($this->table_students) . " where weid = :weid AND id=:id AND schoolid=:schoolid ORDER BY id DESC", array(':weid' => $weid, ':id' => $it['sid'], ':schoolid' => $schoolid));
			$item = pdo_fetch("SELECT * FROM " . tablename ( 'mc_members' ) . " where uniacid = :uniacid AND uid=:uid ORDER BY uid DESC", array(':uid' => $it['uid'], ':uniacid' => $_W ['uniacid'])); 

		    $userinfo = iunserializer($it['userinfo']);
		    
		 include $this->template('user');
          }else{
         include $this->template('bangding');
          }        
?>