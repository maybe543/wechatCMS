<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

if('save'==$_GPC['submit']) {

	// 接受提交数据并验证
	$GandlPuzzleModel = new GandlPuzzleModel();
	$puzzle=$_GPC['puzzle'];
	if(false===$GandlPuzzleModel->create($puzzle)){
		returnError('验证出错：'.$GandlPuzzleModel->getError());
	}
	
	// 业务处理
	$GandlPuzzleModel->__set('uniacid',$_W['uniacid']);
	$GandlPuzzleModel->__set('detail',htmlspecialchars_decode($GandlPuzzleModel->__get('detail')));
	$GandlPuzzleModel->__set('ad',htmlspecialchars_decode($GandlPuzzleModel->__get('ad')));
	$time=$GandlPuzzleModel->__get('time');
	if(!empty($time) && count($time)>0){
		foreach($time as $k=>$v){
			$GandlPuzzleModel->__set($k.'_time',strtotime($v));
		}
		$GandlPuzzleModel->__unset('time');
	}
	$GandlPuzzleModel->__set('share',iserializer(array(
		'title'=>$GandlPuzzleModel->__get('share_title'),
		'img'=>$GandlPuzzleModel->__get('share_img'),
		'desc'=>$GandlPuzzleModel->__get('share_desc')
	)));
	$GandlPuzzleModel->__unset('share_title');
	$GandlPuzzleModel->__unset('share_img');
	$GandlPuzzleModel->__unset('share_desc');
	
	// 保存数据
	if(false===$GandlPuzzleModel->save()){
		returnError('操作失败，请重试');
	}

	returnSuccess('活动修改成功',$this->createWebUrl('list'));

}else{

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
	
	$puzzle['share']=iunserializer($puzzle['share']);
	$puzzle['share_title']=$puzzle['share']['title'];
	$puzzle['share_img']=$puzzle['share']['img'];
	$puzzle['share_desc']=$puzzle['share']['desc'];

	load()->func('tpl');
	include $this->template('web/edit');
}



?>