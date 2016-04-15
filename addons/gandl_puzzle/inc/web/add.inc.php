<?php
//session_start();
//$_SESSION['__:proxy:openid'] = 'oyIjYt9lQx9flMXl9F9NiAqrJd3g';
//debug
global $_W, $_GPC;

if('add'==$_GPC['submit']) {

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
	if(false===$GandlPuzzleModel->add()){
		returnError('操作失败，请重试');
	}

	returnSuccess('解密活动创建成功',$this->createWebUrl('list'));

}else{
	load()->func('tpl');
	include $this->template('web/add');
}



?>