<?php
/**
 * fwei_forms 通用表单
 * ============================================================================
 * * 版权所有 2005-2012 fwei.net，并保留所有权利。
 *   网站地址: http://www.fwei.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: fwei.net  / 1331305@qq.com
 *
 **/

global $_GPC,  $_W;
$uniacid = $_W["uniacid"];
$rid = intval( $_GPC['id'] );
$act = $_GPC['act'];

//表单基本信息
$forms = pdo_fetch('SELECT * FROM '.tablename('fwei_forms')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($forms) ){
	message('参数错误!', '',  'error');
}

//取出调研问题列表
$attrs = pdo_fetchall('SELECT * FROM '.tablename('fwei_forms_attrs')." WHERE formid = :formid AND uniacid = :uniacid ORDER BY sort ASC, attr_id ASC", array(':uniacid' => $uniacid, ':formid'=>$forms['formid']) );
foreach ($attrs as $key => $val) {
	$attrs[$key]['extra'] = explode("\n", $val['extra']);
}

if ( $act == 'detail') {
	$fid = intval($_GPC['fid']);
	$fans = pdo_fetch('SELECT * FROM '.tablename('fwei_forms_fans').' WHERE fid = :fid', array(':fid'=>$fid));
	if( empty($fans) ){
		message('参数错误!',  '', 'error');
	}
	if (checksubmit('submit')) {
		$status = intval($_GPC['status']);
		$status = $status ? 1 : 0;
		pdo_update('fwei_forms_fans', array('status'=>$status, 'updated'=>TIMESTAMP), array('fid'=>$fid) );
		message('操作成功!',  $this->createWebUrl('values', array('id'=>$forms['rid'], 'fid'=>$fid, 'act'=>'detail')));
	}
	$values = pdo_fetchall('SELECT id,rid,formid,fid,attr_id,GROUP_CONCAT(attr_value) as attr_value FROM '.tablename('fwei_forms_values').' WHERE formid = :formid AND fid = :fid GROUP BY attr_id', array(':formid'=>$forms['formid'], ':fid'=>$fans['fid']), 'attr_id');
	include $this->template('values_detail');
} elseif( $act == 'delete' ){
	$fid = intval($_GPC['fid']);
	pdo_delete('fwei_forms_fans', array('fid'=>$fid));
	pdo_delete('fwei_forms_values', array('fid'=>$fid, 'formid'=>$forms['formid']));
	pdo_update('fwei_forms', 'total=total-1', array('formid'=>$forms['formid']));
	message('操作成功!',  $this->createWebUrl('values', array('id'=>$forms['rid'])));
} else {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;

	$sql = 'SELECT * FROM '.tablename('fwei_forms_fans')." WHERE formid = :formid ORDER BY fid DESC ";
	$fanslist = pdo_fetchall("{$sql} LIMIT ". ($pindex -1) * $psize . ',' .$psize , array(':formid'=>$forms['formid']) );
	$total = pdo_fetchcolumn( "SELECT COUNT(*) FROM ({$sql}) AS t", array(':formid'=>$forms['formid']) );
	$pager = pagination($total, $pindex, $psize);

	foreach ($fanslist as &$val) {
		$results = pdo_fetchall('SELECT GROUP_CONCAT(attr_value) as attr_value, attr_id FROM '.tablename('fwei_forms_values').' WHERE formid = :formid AND fid = :fid GROUP BY attr_id', array(':formid'=>$forms['formid'], ':fid'=>$val['fid']), 'attr_id');
		$tmp = array();
		foreach ($attrs as $question) {
			$tmp[$question['attr_id']] = $results[$question['attr_id']]['attr_value'];
		}
		$val['totals']	=	$tmp;
	}
	include $this->template('values_list');
}