<?php
global $_GPC, $_W;
checkauth();	
$sql = 'SELECT `status` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
$cardstatus = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));	
		$rid = intval($_GPC['rid']);
		
		$uid = $_W['member']['uid'];
		
		$prize = pdo_fetchall("SELECT * FROM ".tablename('nsign_prize')." WHERE rid = :rid AND uid = :uid ORDER BY time DESC ", array(':rid' => $rid, ':uid' => $uid ));
		
		if (!empty($rid)) {
		
			$reply = pdo_fetch("SELECT * FROM ".tablename('nsign_reply')." WHERE rid = :rid ", array(':rid' => $rid));			
 		
		}
		
		$Picurl = tomedia($reply['picture']);
		
		include $this->template('prize');