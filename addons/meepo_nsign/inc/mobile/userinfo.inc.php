<?php
global $_GPC, $_W;
checkauth();	
$sql = 'SELECT `status` FROM ' . tablename('mc_card') . " WHERE `uniacid` = :uniacid";
$cardstatus = pdo_fetch($sql, array(':uniacid' => $_W['uniacid']));	
$rid = intval($_GPC['rid']);
		
$uid = $_W['member']['uid'];
$$fields = array('realname','mobile');		
$profile = mc_fetch($uid, $fields);
		
include $this->template('userinfo');