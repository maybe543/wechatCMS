<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

$this->_doMobileAuth();
$user=$this->_user;
$is_user_infoed=$this->_is_user_infoed;

$this->_doMobileInitialize();
$puzzle=$this->_puzzle;
$puzzle_clues=$this->_puzzle_clues;
$is_puzzle_end=$this->_is_puzzle_end;
$friend=$this->_friend;

$mine;		// 我的参与信息
$mine_clues;// 我目前收集到的线索
$friendth;	// 朋友的参与信息

$cmd=$_GPC['cmd']; // 请求命令

if('clue_exchange'==$cmd){ // 交换线索
	
	// 如果解密活动结束,则不允许再交换
	if($this->_is_puzzle_end==1){
		returnError('该解密活动已结束，无法交换线索了');
	}

	// 获取双方参与解密的信息
	$mine = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_user['uid']));
	if(empty($mine)){
		returnajaxError('朋友，你得先参与该活动才能交换线索');
	}

	$friendth = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_friend));
	if(empty($friendth)){
		returnajaxError('对方好像中途退出了...');
	}

	// 判断双方是否已经交换过
	$friendth_froms=empty($friendth['froms'])?array():explode(',',$friendth['froms']);
	if(in_array($this->_user['uid'],$friendth_froms)){
		returnError('您已与对方交换过了');
	}
	$mine_froms=empty($mine['froms'])?array():explode(',',$mine['froms']);
	if(in_array($this->_friend,$mine_froms)){
		returnError('你们已经交换过了');
	}

	// 执行线索交换
	$friendth_froms[]=$this->_user['uid'];
	$friendth_clues=empty($friendth['clues'])?array():explode(',',$friendth['clues']);
	$friendth_clues[]=$mine['clue_idx'];
	$friendth_clues=array_unique($friendth_clues);

	$friendth_up=array(
		'clues'=>implode(',',$friendth_clues),
		'froms'=>implode(',',$friendth_froms)
	);
	$friendth_result=pdo_update('gandl_puzzle_user', $friendth_up, array('id' => $friendth['id'], 'uniacid' => $_W['uniacid']));
	if(false===$friendth_result){
		returnError('交换失败，请重试');
	}

	$mine_froms[]=$friendth['user_id'];
	$mine_clues=empty($mine['clues'])?array():explode(',',$mine['clues']);
	$mine_clues[]=$friendth['clue_idx'];
	$mine_clues=array_unique($mine_clues);

	$mine_up=array(
		'clues'=>implode(',',$mine_clues),
		'froms'=>implode(',',$mine_froms)
	);
	$mine_result=pdo_update('gandl_puzzle_user', $mine_up, array('id' => $mine['id'], 'uniacid' => $_W['uniacid']));
	if(false===$mine_result){
		returnError('交换失败，请重试');
	}
	
	returnSuccess('线索交换成功！');

}else if('clue_froms'==$cmd){ // 我寻找到的线索来源

	// 获取我参与解密的信息
	$mine = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_user['uid']));
	if(empty($mine)){
		returnajaxError('朋友，你得先参与该活动才能获取线索');
	}

	//线索来源类型 1初始线索 2交换线索
	//线索idx
	//线索con
	//线索来源详情
	$list=array();
	$list[]=array(
		'type'=>1,
		'clue_idx'=>$mine['clue_idx'],
		'clue_con'=>$mine['clue_con']
	);

	// 获取与交换者本人有关系的用户朋友以及他们提供的线索
	if(!empty($mine['froms'])){
		$froms=explode(',',$mine['froms']);

		//$sql = 'SELECT * FROM ' . tablename('account') . ' WHERE `acid` > :acid';
		//$params = array(':acid' => '400');
		$fromers = pdo_fetchall('SELECT * FROM ' . tablename('gandl_puzzle_user') . ' WHERE uniacid=:uniacid AND puzzle_id=:puzzle_id AND user_id IN('.implode(',',$froms).') ', array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id']));
		/**
		$fromers=$PuzzleUserModel->where(array(
			'puzzle_id'=>array('eq',$this->_puzzle['id']),	
			'user_id'=>array('in',$froms)
		))->select(); // ->order("substring_index('".$froms."',user_id,1)")
		**/

		$fromerInfos = mc_fetch($froms, array('nickname','avatar'));
		/**
		$fromerInfos=array();
		for($i=0;$i<count($fromerRets);$i++){
			$fromerInfos[$fromerRets[$i]['uid']]=$fromerRets[$i];
		}
		**/

	/**
		$UserModel = D('User');
		$fromerInfos=$UserModel->getField('id,name,avatar');
	**/

		for($i=0;$i<count($fromers);$i++){
			$list[]=array(
					'type'=>2,
					'clue_idx'=>$fromers[$i]['clue_idx'],
					'clue_con'=>$fromers[$i]['clue_con'],
					'fromer'=>$fromerInfos[$fromers[$i]['user_id']],
			);
		}
		
		//$this->assign('fromers',$fromers);
	}
	


	returnSuccess('线索获取成功！',array(
		'start'=>count($list), // 目前不分页，start即表示列表数据量
		'more'=>'0',
		'list'=>$list,
		'repeat'=>(count($list)-count(explode(',',$mine['clues']))) // 重复的线索个数
	));

}else if('answer'==$cmd){ // 用户提交解答

	if($this->_is_puzzle_end==1){
		returnError('该解密活动已结束，无法提交了');
	}

	$answer=$_GPC['answer'];
	if(empty($answer)){
		returnError('您还没填写解答内容');
	}
	if(strlen($answer)>100){
		returnError('你觉得答案可能会这么多字吗？！');
	}

	// 获取我与该活动的关系并验证
	$mine = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_user['uid']));
	if(empty($mine)){
		returnError('您还没有参与该解密！');
	}
	$mine_clues=empty($mine['clues'])?array():explode(',',$mine['clues']);
	if(count($mine_clues)<$this->_puzzle['keys_least']){
		returnError('至少获得'.$this->_puzzle['keys_least'].'个线索才能解答');
	}

	$mine_up=array(
		'answer'=>$answer,
		'answer_time'=>TIMESTAMP
	);
	$mine_result=pdo_update('gandl_puzzle_user', $mine_up, array('id' => $mine['id'], 'uniacid' => $_W['uniacid']));
	if(false===$mine_result){
		returnError('抱歉，提交失败，重新试试看呢');
	}

	return $this->returnSuccess('提交成功！');

}else if('ranklist'==$cmd){ // 排行榜

	if($this->_is_puzzle_end==0){
		returnError('活动还没结束，无法查看排行');
	}

	$start=$_GPC['start'];// st(start):当前已加载记录数(按类型累计)
	if(!isset($start) || empty($start) || intval($start<=0)){
		$start=0;
	}else{
		$start=intval($start);
	}
	$limit=10;

	$list = pdo_fetchall('SELECT user_id,clues,answer_time FROM ' . tablename('gandl_puzzle_user') . ' WHERE uniacid=:uniacid AND puzzle_id=:puzzle_id AND answer=:answer ORDER BY answer_time ASC,id ASC limit '.$start.','.$limit.' ', array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':answer' => $this->_puzzle['truth']));
	/**
	$PuzzleUserModel=D('PuzzleUser');
	$list=$PuzzleUserModel->where(array(
		'puzzle_id'=>$this->_puzzle['id'],
		'answer'=>$this->_puzzle['truth']
	))->order('answer_time ASC,id ASC')->limit($start.','.$limit)->field('user_id,clues,answer_time')->select();
	**/

	$more=1;
	if(empty($list) || count($list)<$limit){
		$more=0;
	}
	$start+=count($list);

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
			$list[$i]['answer_time']=time_to_text($list[$i]['answer_time']-$this->_puzzle['start_time']);
		}
	}

	returnSuccess('线索获取成功！',array(
		'start'=>$start,
		'more'=>$more,
		'list'=>$list
	));

}else{


	//print_r($_COOKIE);
	//checkauth();
	/**
	echo "<p>_SESSION['uniacid']:".$_SESSION['uniacid'].'</p>';
	echo "<p>_SESSION['acid']:".$_SESSION['acid'].'</p>';

	echo "<p>_SESSION['openid']:".$_SESSION['openid'].'</p>';

	echo "<p>_SESSION['oauth_openid']:".$_SESSION['oauth_openid'].'</p>';
	echo "<p>_SESSION['oauth_acid']:".$_SESSION['oauth_acid'].'</p>';
	echo "<p>_W['member']:";
	print_r($_W['member']);
	echo '</p>';
	echo "<p>_W['openid']:".$_W['openid'].'</p>';
	echo "<p>_W['fans']:";
	print_r($_W['fans']);
	echo '</p>';
	echo "<p>_SESSION['userinfo']:";
	print_r($_SESSION['userinfo']);
	echo '</p>';

	echo "<p>SERVER['QUERY_STRING']:".$_SERVER['QUERY_STRING'].'</p>';
	
	
	echo "<p>_W['oauth_account']:";
	print_r($_W['oauth_account']);
	echo '</p>';

	load()->func('tpl');
	include $this->template('test');
	***/


			//$this->assign('puzzle',$this->_puzzle);
		//$this->assign('is_puzzle_end',$this->_is_puzzle_end);
		//$this->assign('is_user_logined',$this->_is_user_logined);
	
	// 加载可能会用到的模块
	load()->model('mc');
	load()->func('tpl');
	
	if($this->_is_puzzle_end==0){// 如果解密未结束，获取我与该解密的关系

		// 如果是从朋友的页面进入，获取该朋友的参与信息，用于交换线索
		if($this->_friend>0){
			$friendth = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_friend));
			if(!empty($friendth)){// 如果朋友已经参与解密活动，则需要获取其个人信息用于展示
				$friendth['user'] = mc_fetch($friendth['user_id'], array('email','mobile','nickname','avatar'));
			}
		}

		if($this->_is_user_infoed==0){ // 如果我还没基本信息，则直接显示页面(页面中引导用户完善基本信息)
			include $this->template('play');
			exit;
		}else{ // 如果我已登录，获取我与该解密活动的关系(没关系则建立关系)
			// 获取解密活动信息
			$mine = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_user['uid']));
			if(empty($mine)) {// 自动建立关系
				// 随机分配一个线索（随机算法为：(解密活动ID+用户ID)%线索的数量）+1
				$clue_idx = $this->_doMobileDistributeClue();
				$clue_con = $this->_puzzle_clues[$clue_idx];
				$mine=array(
					'uniacid'=>$_W['uniacid'],
					'puzzle_id'=>$this->_puzzle['id'],
					'user_id'=>$this->_user['uid'],
					'clue_idx'=>$clue_idx,
					'clue_con'=>$clue_con,
					'clues'=>$clue_idx,
					'join_time'=>time()
				);
				pdo_insert('gandl_puzzle_user', $mine);
				$mine['id'] = pdo_insertid();
				if(false===$mine['id']){
					return message('加入失败，重新进入页面试试看呢','','error');
				}
			}

			// 根据我收集到的线索idxs,转换成线索文字
			$mine_clues=array();
			$mcs = explode(',', $mine['clues']);
			foreach($mcs as $i){
				$mine_clues[$i]=$this->_puzzle_clues[intval($i)];
			}
			
			// 如果是从朋友的页面进入，判断我是否已经和对方交换过
			$mine_froms=empty($mine['froms'])?array():explode(',',$mine['froms']);
			if(in_array($friendth['user_id'],$mine_froms)){
				$friendth['exchanged']=1;
			}
			
			// 我与朋友的几种情况：
			// 1 朋友没参与：不显示我与朋友的关系
			// 2 朋友已参与，我没参与(实际上是没有我的信息所以没法自动参与)：显示支持朋友引导参与
			// 3 朋友已参与，我已参与，还没交换：显示我的信息，同时显示朋友希望与我交换
			// 4 朋友已参与，我已参与，已交换：显示我的信息

		}
	}else{ // 解密已结束，揭晓
		// 获取我与该活动的关系
		$mine = pdo_fetch("select * from " . tablename('gandl_puzzle_user') . " where uniacid=:uniacid and puzzle_id=:puzzle_id and user_id=:user_id ", array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':user_id' => $this->_user['uid']));
		if(!empty($mine)){ // 如果我参与了该活动，需要判断我是否挑战成功(1：是否回答正确，2：是否在中奖排名内)
			if($mine['answer']===$this->_puzzle['truth']){ // 回答正确
				// 附加回答正确的依据
				$mine['answer_result']=1;// answer_result=1 回答正确

				if(empty($mine['rank']) || $mine['rank']<=0){ // 如果我还没有缓存排行
					// 计算我的排名
					/**
					$rank=$PuzzleUserModel->where(array(				// 我的排名是：
						'puzzle_id'=>array('eq',$this->_puzzle['id']),	// 在该解密活动中
						'answer_time'=>array('lt',$mine['answer_time']),// 比我快解答
						'answer'=>array('eq',$this->_puzzle['truth'])	// 且答案正确的
					))->count('user_id')+1;								// 用户数+1
					*/
					$rank = pdo_fetchcolumn('SELECT COUNT(user_id) FROM '.tablename('gandl_puzzle_user').'WHERE uniacid=:uniacid AND puzzle_id=:puzzle_id AND answer_time<:answer_time AND answer=:truth',array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':answer_time' => $mine['answer_time'],':truth' => $this->_puzzle['truth']))+1;
					
					// 为了防止解答时间完全一致，有可能出现并列排名的现象，所以需要根据加入的先后次序来再次判断排名（先加入的排名靠前）
					// 获取和我并列排名中，比我早加入的人的个数
					/**
					$fronts=$PuzzleUserModel->where(array(				
						'puzzle_id'=>array('eq',$this->_puzzle['id']),	// 在该解密活动中
						'answer_time'=>array('eq',$mine['answer_time']),// 和我一样快解答
						'answer'=>array('eq',$this->_puzzle['truth']),	// 且答案正确的
						'id'=>array('lt',$mine['id'])					// 且在我之前加入的
					))->count('user_id');								// 用户数
					*/
					$fronts = pdo_fetchcolumn('SELECT COUNT(user_id) FROM '.tablename('gandl_puzzle_user').'WHERE uniacid=:uniacid AND puzzle_id=:puzzle_id AND answer_time=:answer_time AND answer=:truth AND id<:mine_id',array(':uniacid' => $_W['uniacid'],':puzzle_id' => $this->_puzzle['id'],':answer_time' => $mine['answer_time'],':truth' => $this->_puzzle['truth'],':mine_id' => $mine['id']));

					$mine['rank']=$rank+$fronts; // 我的实际排行
					
					$mineup=array(
						'rank'=>$mine['rank']
					);

					// 如果我的排行在中奖范围内，需要生成中奖码
					if($mine['rank']<=$this->_puzzle['award']){
						$rand=rand_words('1234567890',4);
						$mine['award_code']=pencode($mine['id'].','.$rand);
						$mineup['award_code']=$mine['award_code'];
					}

					// 缓存我的排行和中奖码
					//$PuzzleUserModel->where(array('id'=>$mine['id']))->save($mineup);
					$result=pdo_update('gandl_puzzle_user', $mineup, array('id' => $mine['id'], 'uniacid' => $_W['uniacid']));
					if(false===$result){
						returnError('访问失败，请刷新');
					}
				}
			}else{
				// 附加回答错误的依据
				$mine['answer_result']=0;// answer_result=0 回答错误
			}

			// 根据我收集到的线索idxs,转换成线索文字
			$mine_clues=array();
			$mcs = explode(',', $mine['clues']);
			foreach($mcs as $i){
				$mine_clues[$i]=$this->_puzzle_clues[intval($i)];
			}
			
			//$this->assign('mine',$mine);
			//$this->assign('mine_clues',$mine_clues);
			
		}
	}

	include $this->template('play');
}



?>