<?php 
require MODULE_ROOT.'/model.php';
global $_W,$_GPC;
$handlers = array('活动列表'=>'list','新增地推活动'=>'post','活动详情'=>'detail','delete','显示添加地推人员的二维码'=>'addnew','查看扫描记录'=>'records','删除地推人员'=>'delstaff');
$operation = isset($_GPC['op']) && in_array($_GPC['op'], $handlers) ? $_GPC['op'] : 'list';
$activityModel = new activity();
$staffModel = new staff();
$qrModel = new qr();
$recordModel = new record();
load()->func('tpl');

if($operation == 'list'){
	$lists = $activityModel->lists();
	if(!empty($lists)){
		foreach ($lists as $key => $item) {
			$lists[$key]['score'] = $recordModel->count_activity($item['activity_id']);
		}
	}
}elseif($operation == 'post'){
	$activityId = isset($_GPC['id']) ? $_GPC['id'] : '';

	if(checksubmit()){
		$activity = $_GPC['activity'];
		$msg = $activity['msg'];
		$new['title'] = $activity['title'];
		$new['remark'] = $activity['remark'];
		$new['msg_title'] = $msg['title'];
		$new['msg_thumb'] = $msg['thumb'];
		$new['msg_remark'] = $msg['remark'];
		$new['msg_link'] = substr($msg['link'], 0,4) == 'http' ? $msg['link'] : 'http://'.$msg['link'];

		if(!empty($activityId)){
			//更新
			$res = $activityModel->modify($new,$activityId);
			if($res){
				message('更新成功',$this->createWebUrl('activity',array('op'=>'list')),'success');
			}else{
				message('更新失败','','error');
			}
		}else{
			//新增
			$res = $activityModel->add_new($new);
			if($res){
				message('添加成功',$this->createWebUrl('activity',array('op'=>'list')),'success');
			}else{
				message('添加失败','','error');
			}
		}
		
	}

	if(!empty($activityId)){
		$activity = $activityModel->item($activityId);
	}
}elseif ($operation == 'delete') {
	$activityId = $_GPC['id'];
	$res = $activityModel->del($activityId);
	if($res){
		$rs = $recordModel->del_activity($activityId);
		if($rs){
			message('删除成功',$this->createWebUrl('activity',array('op'=>'list')),'success');
		}else{
			message('删除推广活动的推广记录时，失败','','error');
		}
		
	}else{
		message('删除失败','','error');
	}
}elseif ($operation == 'addnew') {
	$url = $_W['siteroot'].'app/'.$this->createMobileUrl('staff',array('op'=>'addnew','activity'=>$_GPC['id']));
	require(IA_ROOT . '/framework/library/qrcode/phpqrcode.php');
	$errorCorrectionLevel = "L";
	$matrixPointSize = "5";
	QRcode::png($url, false, $errorCorrectionLevel, $matrixPointSize);
	exit();
}elseif($operation == 'detail'){
	$activityId = $_GPC['id'];
	$staffs = $staffModel->activity($activityId);
	$activity = $activityModel->item($activityId);
	$activity['score'] = $recordModel->count_activity($activityId);
	if(!empty($staffs)){
		load()->model('mc');
		$acid = pdo_fetchcolumn("SELECT `acid` FROM ".tablename('account_wechats')." WHERE `uniacid`=:uniacid",array(':uniacid'=>$_W['uniacid']));
		foreach ($staffs as $key => $item) {
			$staffs[$key]['hasQr'] = $qrModel->isValidKeyword('ditui_'.$item['staff_id']);//获取其二维码信息
			$staffs[$key]['wx'] = mc_fansinfo($item['openid'],$acid,$_W['uniacid']);//获取地推人员微信信息
			$staffs[$key]['score'] = $recordModel->count_staff($item['staff_id']);//获取地推人员成绩
		}
	}
}elseif($operation == 'records'){
	
	$page_index = isset($_GPC['page']) ? $_GPC['page'] : 1;
	$page_size = isset($_GPC['size']) ? $_GPC['size'] : 10;
	
	if(isset($_GPC['activity'])){
		//查看活动的推广记录
		$page_total = $recordModel->count_activity($_GPC['activity']);
		$pagination = pagination($page_total, $page_index, $page_size);

		$records = $recordModel->activity($_GPC['activity'],$page_index,$page_size);
		

		$activity = $activityModel->item($_GPC['activity']);
		$info = '推广活动：'.$activity['title'];
	}elseif (isset($_GPC['staff'])) {
		//查看某地推人员的推广记录
		$page_total = $recordModel->count_staff($_GPC['staff']);
		$pagination = pagination($page_total, $page_index, $page_size);
		$records = $recordModel->staff($_GPC['staff'],$page_index,$page_size);
		

		$staff = $staffModel->item($_GPC['staff']);
		$info = '地推人员：'.$staff['wx']['nickname'];
	}
}elseif ($operation == 'delstaff') {
	$staffId = $_GPC['staff'];
	$res = $staffModel->del($staffId);
	if($res){
		$rs = $recordModel->del_staff($staffId);
		if($rs){
			message('删除成功',$this->createWebUrl('activity',array('op'=>'detail','id'=>$_GPC['activity'])),'success');
		}else{
			message('删除地推员推广记录时，失败','','error');
		}
		
	}else{
		message('删除失败','','error');
	}
}

include $this->template('activity');