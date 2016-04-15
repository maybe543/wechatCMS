<?php

	$rule = pdo_fetchcolumn('SELECT rule FROM '.tablename('hc_hunxiao_rules')." WHERE weid = :weid" , array(':weid' => $weid));
	$id = pdo_fetchcolumn('SELECT id FROM '.tablename('hc_hunxiao_member')." WHERE weid = :weid AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));

	include $this->template('rule');
?>