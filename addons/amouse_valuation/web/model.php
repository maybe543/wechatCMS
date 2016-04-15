<?php
 //shizhongying qq:214983937
//机型管理
global $_GPC, $_W;
$op= $_GPC['op'] ? $_GPC['op'] : 'display';
$weid= $_W['uniacid'];
load()->func('tpl');
if($op == 'display') {
	$pindex= max(1, intval($_GPC['page']));
	$psize= 20; //每页显示 
	$condition= "WHERE `weid` = $weid";
	if(!empty($_GPC['keyword'])) {
		$condition .= " AND title LIKE '%".$_GPC['keyword']."%'";
	}
	$list= pdo_fetchall('SELECT * FROM '.tablename('amouse_valuation_mobile_model')." $condition  ORDER BY createtime ASC LIMIT ".($pindex -1) * $psize.','.$psize);
	$total= pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('amouse_valuation_mobile_model').$condition);
	$pager= pagination($total, $pindex, $psize);
}elseif($op == 'post') {
	$id= intval($_GPC['id']);
	if($id > 0) {
		$item= pdo_fetch('SELECT * FROM '.tablename('amouse_valuation_mobile_model')." WHERE weid=:weid AND id=:id", array(':weid' => $this->weid, ':id' => $id));
	}

	if(checksubmit('submit')) {
		$title= trim($_GPC['title']) ? trim($_GPC['title']) : message('请填写机型名称！');
		$logo= trim($_GPC['thumb']) ? trim($_GPC['thumb']) : message('请上传机型图片！');
		$insert= array('title' => $title, 'logo' => $logo, 'weid' => $weid, 'createtime' => TIMESTAMP);

		if(empty($id)) {
			pdo_insert('amouse_valuation_mobile_model', $insert);
			!pdo_insertid() ? message('保存机型数据失败, 请稍后重试.', 'error') : '';
		} else {
				if(pdo_update('amouse_valuation_mobile_model', $insert, array('id' => $id)) === false) {
				message('更新机型数据失败, 请稍后重试.', 'error');
			}
		}
		message('更新机型数据成功！', $this->createWebUrl('model', array('op' => 'display', 'name' => 'amouse_valuation')), 'success');
	}
}elseif($op == 'del') { //删除
	if(isset($_GPC['delete'])) {
		$ids= implode(",", $_GPC['delete']);
		$sqls= "delete from  ".tablename('amouse_valuation_mobile_model')."  where id in(".$ids.")";
		pdo_query($sqls);
		message('删除成功！', referer(), 'success');
	}
	$id= intval($_GPC['id']);
	$temp= pdo_delete("amouse_valuation_mobile_model", array("weid" => $_W['uniacid'], 'id' => $id));
	message('删除数据成功！', $this->createWebUrl('model', array('op' => 'display', 'name' => 'amouse_valuation')), 'success');
}
include $this->template('web/model');
?>
