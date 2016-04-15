<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

	load()->model('mc');

	// 获取当前解密活动信息
	
	$id = intval($_GPC['id']);
	if(empty($id)) {
		message('抱歉，传递的参数错误！', '', 'error');
	}

	$puzzle = pdo_fetch("select * from " . tablename('gandl_puzzle') . " where uniacid=:uniacid and id=:id ", array(':uniacid' => $_W['uniacid'],':id' => $id));
	if(empty($puzzle)) {
		message('抱歉，没有相关数据！', '', 'error');
	}

	// 业务处理
	$puzzle['time']=array(
		'start'=>date('Y-m-d H:i:s',$puzzle['start_time']),
		'end'=>date('Y-m-d H:i:s',$puzzle['end_time']),
	);

	// 统计：参与人数
	$static_all = pdo_fetchcolumn("select count(a.id) from " . tablename('gandl_puzzle_user') . " a where a.uniacid=:uniacid and a.puzzle_id=:puzzle_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $puzzle['id']));
	// 统计：解答人数
	$static_answer = pdo_fetchcolumn("select count(a.id) from " . tablename('gandl_puzzle_user') . " a where a.uniacid=:uniacid and a.puzzle_id=:puzzle_id and a.answer IS NOT NULL", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $puzzle['id']));
	// 统计：答对人数
	$static_right = pdo_fetchcolumn("select count(a.id) from " . tablename('gandl_puzzle_user') . " a where a.uniacid=:uniacid and a.puzzle_id=:puzzle_id and a.answer=:truth ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $puzzle['id'],':truth'=>$puzzle['truth']));


	// 获取当前解密活动参与者信息
	$where = '';
	$params = array(':uniacid' => $_W['uniacid'],':puzzle_id' => $puzzle['id']);
	if (isset($_GPC['status'])) { // 1 已解答 2 已答对
		$status=intval($_GPC['status']);
		if('1'==$status){
			$where.=' and a.answer IS NOT NULL';
		}else if('2'==$status){
			$where.=' and a.answer = :truth';
			$params[':truth'] = $puzzle['truth'];
		}
	}

	$total = pdo_fetchcolumn("select count(a.id) from " . tablename('gandl_puzzle_user') . " a where a.uniacid=:uniacid and a.puzzle_id=:puzzle_id " . $where . "", $params);
	$pindex = max(1, intval($_GPC['page']));
	$psize = 12;
	$pager = pagination($total, $pindex, $psize);
	$start = ($pindex - 1) * $psize;
	$limit .= " LIMIT {$start},{$psize}";
	$list = pdo_fetchall("select a.* from " . tablename('gandl_puzzle_user') . " a where a.uniacid=:uniacid and a.puzzle_id=:puzzle_id " . $where . " order by a.answer_time ASC,a.id ASC  " . $limit, $params);
	if(!empty($list)){ // 附加用户信息
		$uids=array();
		foreach($list as $v){
			$uids[]=$v['user_id'];
		}
		$users = mc_fetch($uids, array('nickname','avatar'));
		/**$users=$UserModel->where(array('id'=>array('IN',$uids)))->getField('id,name,avatar');**/
		for($i=0;$i<count($list);$i++){
			$user=$users[$list[$i]['user_id']];
			$user['avatar']=VP_AVATAR($user['avatar'],'s');
			$list[$i]['User']=$user;
			$list[$i]['answer_time']=time_to_text($list[$i]['answer_time']-$puzzle['start_time']);
		}
	}


	load()->func('tpl');
	include $this->template('web/view');




?>