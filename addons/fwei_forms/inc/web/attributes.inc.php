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
$act = $_GPC['act'] == '' ? 'list' : $_GPC['act'];
$attr_id = intval( $_GPC['attr_id'] );

//表单基本信息
$forms = pdo_fetch('SELECT * FROM '.tablename('fwei_forms')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($forms) ){
	message('参数错误!', '',  'error');
}

if( $act == 'add' ){
	
	//添加
	if (checksubmit('submit')) {
		if( empty($_GPC['title']) ){
			message('请输入字段名称');
		}
		$extra = trim($_GPC['extra'], "\n");
		
		if( !in_array($_GPC['type'], array('text', 'textarea','date','datetime', 'images')) && empty($extra) ){
			message('单选、多选、下拉列表类型必须输入参数');
		}
		$insert_data = array(
			'title' =>	$_GPC['title'],
			'description'	=>	$_GPC['description'],
			'type'	=>	$_GPC['type'],
			'extra'	=>	$extra,
			'defvalue'	=>	$_GPC['defvalue'],
			'rule'	=>	$_GPC['rule'],
			'is_must'	=>	$_GPC['is_must'] ? 1 : 0,
			'is_show'	=>	$_GPC['is_show'] ? 1 : 0,
			'sort'	=>	intval( $_GPC['sort'] ),
		);
		if( $attr_id ){
			pdo_update('fwei_forms_attrs', $insert_data, array('uniacid' => $uniacid, 'attr_id'=>$attr_id) );
		} else {
			$insert_data['formid'] = $forms['formid'];
			$insert_data['rid'] = $forms['rid'];
			$insert_data['uniacid'] = $uniacid;
			pdo_insert('fwei_forms_attrs', $insert_data);
		}
		message('操作成功!', $this->createWebUrl('attributes', array('id'=>$rid)));
	} else {
		$item = array(
			'type'	=>	'radio',
			'is_must'	=>	1,
			'is_show'	=>	0,
		);

		if( $attr_id ){
			$finfo = pdo_fetch('SELECT * FROM '.tablename('fwei_forms_attrs')." WHERE attr_id = :attr_id AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':attr_id'=>$attr_id) );
			if( $finfo ){
				$item = $finfo;
				unset($finfo);
			}
		}
		
		include $this->template('attrs_add');
	}
}elseif ( $act == 'delete' ) {
	pdo_delete('fwei_forms_attrs', array('rid'=>$rid, 'attr_id'=>$attr_id));
	pdo_delete('fwei_forms_values', array('rid'=>$rid, 'attr_id'=>$attr_id));
	message('操作成功!', $this->createWebUrl('attributes', array('id'=>$rid)));
} else {
	//取出调研问题列表
	$attrs = pdo_fetchall('SELECT * FROM '.tablename('fwei_forms_attrs')." WHERE formid = :formid AND uniacid = :uniacid ORDER BY sort ASC, attr_id ASC", array(':uniacid' => $uniacid, ':formid'=>$forms['formid']) );

	foreach ($attrs as $key => &$val) {
		$val['extra_txt'] = nl2br($val['extra']);
		$val['type_txt'] = $attr_types[$val['type']];
	}
	include $this->template('attrs_list');
}