<?php
	$this->Judge();
	$advs = pdo_fetchall("select * from " . tablename('auction_adv') . " where enabled=1 and weid= '{$_W['uniacid']}'");
	foreach ($advs as &$adv) {
		if (substr($adv['link'], 0, 5) != 'http:') {
			$adv['link'] = "http://" . $adv['link'];
		}
	}
	unset($adv);
	$category = pdo_fetchall("SELECT * FROM " . tablename('auction_category') . " WHERE weid = '{$_W['uniacid']}' and enabled=1 ORDER BY displayorder DESC");
	$pindex = 1;
	$psize = 10;
	$nowtime = TIMESTAMP;
	$contion ='';
	if (!empty($_GPC['gid'])) {
		$contion.="and categoryid = '{$_GPC['gid']}'";
	}
	$list = pdo_fetchall("SELECT * FROM ".tablename('auction_goodslist')." WHERE uniacid = '{$weid}' and g_status = 2 and end_time < $nowtime $contion ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);

	include $this->template('area');
?>