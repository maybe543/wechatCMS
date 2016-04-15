<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;


	// 接受提交数据并验证
	$id=$_GPC['id'];
	$uid=$_GPC['uid'];
	$award_code=$_GPC['award_code'];
	$award_remark=$_GPC['award_remark'];

	if(empty($id) || empty($uid) || empty($award_code)){
		returnError('缺少参数');
	}

	// 获取活动信息
	$puzzle = pdo_fetch("select * from " . tablename('gandl_puzzle') . " where uniacid=:uniacid and id=:id ", array(':uniacid' => $_W['uniacid'],':id' => $id));
	if(empty($puzzle)) {
		returnError('活动不存在');
	}
	if($puzzle['end_time']>time()){
		returnError('活动还没结束，不能兑奖');
	}

	// 获取兑奖者信息
	$puser = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $id,':user_id' => $uid));
	if(empty($puser)){
		returnError('没有该参与者信息');
	}
	if(empty($puser['answer']) || $puser['answer']!=$puzzle['truth']){
		returnError('该参与者回答错误，没有中奖');
	}
	if(empty($puser['rank'])){
		returnError('该参与者还没有生成名次');
	}
	if($puser['rank']>$puzzle['award']){
		returnError('该参与者的名次不在中奖范围内');
	}

	// 验证兑奖
	if(!empty($puser['award_time'])){
		returnError('该用户已经兑过奖了');
	}
	if($award_code!=$puser['award_code']){
		returnError('兑奖码不正确');
	}

	$prize_up=array(
		'award_time'=>TIMESTAMP,
		'award_remark'=>$award_remark
	);
	$prize_result=pdo_update('gandl_puzzle_user', $prize_up, array('id' => $puser['id'], 'uniacid' => $_W['uniacid']));
	if(false===$prize_result){
		returnError('抱歉，兑奖失败，重新试试看呢');
	}

	returnSuccess('兑奖成功!');

?>