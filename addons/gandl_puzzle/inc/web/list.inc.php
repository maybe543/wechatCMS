<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

/**
$rid = intval($_GPC['rid']);
//查询是否有商户网点权限
$modules = uni_modules($enabledOnly = true);
$modules_arr = array();
$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
if(in_array('stonefish_branch',$modules_arr)){
	$stonefish_branch = true;
}
//查询是否有商户网点权限
//所有奖品类别
$award = pdo_fetchall("select * from " . tablename('stonefish_scratch_prize') . " where rid = :rid and uniacid=:uniacid order by `id` asc", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
foreach ($award as $k =>$awards) {
	$award[$k]['num'] = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_scratch_award') . " where rid = :rid and uniacid=:uniacid and prizetype='".$awards['id']."'", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
}
//所有奖品类别
//导出标题		
if($_GPC['status']==0){
	$statustitle = '被取消'.$_GPC['award'];
}
if($_GPC['status']==1){
	$statustitle = '未兑换'.$_GPC['award'];
}
if($_GPC['status']==2){
	$statustitle = '已兑换'.$_GPC['award'];
}
if($_GPC['status']==''){
	$statustitle = '全部'.$_GPC['award'];
}
//导出标题        
if (empty($rid)) {
	message('抱歉，传递的参数错误！', '', 'error');
}
**/
$where = '';
$params = array(':uniacid' => $_W['uniacid']);
if (isset($_GPC['status'])) { // 1 进行中 2 未开始 3 已结束
	$status=intval($_GPC['status']);
	if('1'==$status){
		$where.=' and a.start_time <= :nowtime and a.end_time >= :nowtime';
	}else if('2'==$status){
		$where.=' and a.start_time > :nowtime';
	}else if('3'==$status){
		$where.=' and a.end_time < :nowtime';
	}

	$params[':nowtime'] = TIMESTAMP;
}

$total = pdo_fetchcolumn("select count(a.id) from " . tablename('gandl_puzzle') . " a where a.uniacid=:uniacid " . $where . "", $params);
$pindex = max(1, intval($_GPC['page']));
$psize = 12;
$pager = pagination($total, $pindex, $psize);
$start = ($pindex - 1) * $psize;
$limit .= " LIMIT {$start},{$psize}";
$list = pdo_fetchall("select a.* from " . tablename('gandl_puzzle') . " a where a.uniacid=:uniacid  " . $where . " order by a.id desc " . $limit, $params);
for($i=0;$i<count($list);$i++){
	// 处理活动状态
	if($list[$i]['start_time'] <= TIMESTAMP && $list[$i]['end_time'] >= TIMESTAMP){
		$list[$i]['status']=1;
	}else if($list[$i]['start_time'] > TIMESTAMP){
		$list[$i]['status']=2;
	}else if($list[$i]['end_time'] < TIMESTAMP){
		$list[$i]['status']=3;
	}
	// 处理活动入口
	$url = $this->createMobileUrl('play', array('pid' => pencode($list[$i]['id'])));
	$list[$i]['surl'] = $url;
	$url = substr($url, 2);
	$url = $_W['siteroot'] . 'app/' . $url;
	$list[$i]['url'] = $url;
}

include $this->template('web/list');
?>