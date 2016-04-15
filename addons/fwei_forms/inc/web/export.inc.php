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
include MODULE_ROOT . '/inc/common.php';
global $_GPC,  $_W;
$uniacid = $_W["uniacid"];
$rid = intval( $_GPC['id'] );

//表单基本信息
$forms = pdo_fetch('SELECT formid, title, stime, etime, num, total FROM '.tablename('fwei_forms')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($forms) ){
	message('参数错误!', '',  'error');
}
$out_data = $tmps = array();
//取出调研问题列表
$tmps[] = 'FID';
$tmps[] = 'OPENID';
$attrs = pdo_fetchall('SELECT attr_id, title FROM '.tablename('fwei_forms_attrs')." WHERE formid = :formid AND uniacid = :uniacid ORDER BY sort ASC, attr_id ASC", array(':uniacid' => $uniacid, ':formid'=>$forms['formid']) );
foreach ($attrs as $key => $val) {
	$tmps[] = $val['title'];
}
$tmps[] = '状态';
$tmps[] = '时间';
$out_data[] = $tmps;
$fanslist = pdo_fetchall('SELECT * FROM '.tablename('fwei_forms_fans').' WHERE formid = :formid ORDER BY fid DESC ' , array(':formid'=>$forms['formid']) );
foreach ($fanslist as $val) {
	$tmps = array(
		$val['fid'],
		$val['openid']
	);
	$results = pdo_fetchall("SELECT GROUP_CONCAT(attr_value SEPARATOR ';') as attr_value, attr_id FROM ".tablename('fwei_forms_values').' WHERE formid = :formid AND fid = :fid GROUP BY attr_id', array(':formid'=>$forms['formid'], ':fid'=>$val['fid']), 'attr_id');
	foreach ($attrs as $question) {
		$tmps[] = $results[$question['attr_id']]['attr_value'];
	}
	$tmps[] = $val['status'] ? '已处理' : '未处理';
	$tmps[] = date('Y-m-d H:i:s', $val['created']);
	$out_data[] = $tmps;
}
toExcel($out_data,"{$forms['title']}.xls");