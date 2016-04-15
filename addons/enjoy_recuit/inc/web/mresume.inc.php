<?php
//这个操作被定义用来呈现 简历管理中心导航菜单
global $_W,$_GPC;
$uniacid=$_W['uniacid'];
$pindex = max(1, intval($_GPC['page']));
$psize = 8;
//查询用户简历
//	$userlist=pdo_fetchall("select * from ".tablename('enjoy_recuit_basic')." where uniacid=".$uniacid." order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$userlist=pdo_fetchall("select a.*,b.avatar as weavatar from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_fans')." as b on a.openid=b.openid where a.uniacid=".$uniacid." order by a.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_basic') . " WHERE uniacid = '{$_W['uniacid']}'");

//var_dump($userlist);
//意向简历
$op=$_GPC['op'];
if($op=='italy'){
	//$userlist=pdo_fetchall("select * from ".tablename('enjoy_recuit_basic')." where uniacid=".$uniacid." and italy=1 order by createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
	$userlist=pdo_fetchall("select a.*,b.avatar as weavatar from ".tablename('enjoy_recuit_basic')." as a left join ".tablename('enjoy_recuit_fans')." as b on a.openid=b.openid where a.uniacid=".$uniacid." and italy=1 order by a.createtime desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('enjoy_recuit_basic') . " WHERE uniacid = '{$_W['uniacid']}' and italy=1");
}

$pager = pagination($total, $pindex, $psize);
//	$list=pdo_fetchall("select * from ".tablename('enjoy_recuit_position')." as a left join ".tablename('enjoy_recuit_position_range')." as b on a.id=b.pid WHERE a.uniacid = '{$_W['uniacid']}' order by hot desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

include $this->template('mresume');