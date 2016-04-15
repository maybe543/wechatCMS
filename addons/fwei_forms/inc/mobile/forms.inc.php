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

global $_GPC, $_W;

load()->model('mc');
load()->func('tpl');
load()->func('communication');
load()->func('file');
include MODULE_ROOT . '/inc/common.php';

$rid = intval( $_GPC['id'] );
$uniacid = $_W['uniacid'];
$openid = $_W['openid'];
$uid = $_W['member']['uid'];
if( empty($openid) ){
	$openid = $_GPC['_openid'];
}
if( empty($uid) ){
	$uid = mc_openid2uid( $openid );
}

//调研基本信息
$forms = pdo_fetch('SELECT * FROM '.tablename('fwei_forms')." WHERE rid = :rid AND uniacid = :uniacid", array(':uniacid' => $uniacid, ':rid'=>$rid) );
if( empty($forms) ){
	message('参数错误!', '',  'error');
}
//问题列表
$attrs = pdo_fetchall('SELECT * FROM '.tablename('fwei_forms_attrs')." WHERE rid = :rid AND formid = :formid ORDER BY sort ASC, attr_id ASC", array(':formid' => $forms['formid'], ':rid'=>$forms['rid']) );

if (checksubmit('submit')) {
	$submit_data = $_GPC['submit_data'];
	if( isset($_FILES['submit_data']) ){
		foreach ($_FILES['submit_data']['name'] as $attr_id => $name){
			$file = array(
				'name'	=>	$name,
				'type'	=>	$_FILES['submit_data']['type'][$attr_id],
				'tmp_name'	=>	$_FILES['submit_data']['tmp_name'][$attr_id],
				'error'	=>	$_FILES['submit_data']['error'][$attr_id],
				'size'	=>	$_FILES['submit_data']['size'][$attr_id],
			);
			$res = file_upload($file);
			$submit_data[$attr_id] = '';
			if( !is_error($res) ){
				$submit_data[$attr_id] = $res['path'];
			}
		}
	}

	$error = '';
	foreach ($attrs as $row) {
		if( $row['is_must'] && empty($submit_data[$row['attr_id']]) ){
			$error .= "· {$row['title']}不能为空\n";
		}elseif( $row['rule'] && !preg_match('/'.$row['rule'].'/u', $submit_data[$row['attr_id']]) ){
			$error .= "· {$row['title']}格式不正确\n";
		}
	}
	if( !empty($error) ){
		die(json_encode( array('s'=>-1, 'msg' => "{$error}")) );
	}
	$fans_data = array(
		'rid'	=>	$forms['rid'],
		'formid'	=>	$forms['formid'],
		'uid'	=>	$uid,
		'openid'	=>	$openid,
		'status'	=>	0,
		'created'	=>	TIMESTAMP,
	);
	pdo_insert('fwei_forms_fans', $fans_data);
	$fid = pdo_insertid();
	if( !$fid ){
		die(json_encode( array('s'=>-1, 'msg' => "保存数据出错！")) );
	}

	foreach ( $submit_data as $attr_id => $arr ) {
		$insert_data = array(
			'rid'	=>	$forms['rid'],
			'formid'	=>	$forms['formid'],
			'fid'	=>	$fid,
			'attr_id'	=>	$attr_id,
		);
		if( is_array($arr) ){
			foreach ( $arr as $val ) {
				$insert_data['attr_value'] = trim($val);
				pdo_insert('fwei_forms_values', $insert_data);
			}
		} else {
			$insert_data['attr_value'] = trim($arr);
			pdo_insert('fwei_forms_values', $insert_data);
		}
	}
	pdo_update('fwei_forms', 'total=total+1', array('rid'=>$forms['rid']));
	//发送通知
	$forms['notice'] = $forms['notice'] ? unserialize( $forms['notice'] ) : array();
	if( $forms['notice']['content'] ){
		if( $forms['notice']['openid'] ){
			$acc = WeAccount::create( $uniacid );
			$send = array();
			$send['touser'] = $forms['notice']['openid'];
			$send['msgtype'] = 'text';
			$send['text'] = array('content' => urlencode($forms['notice']['content']));
			$acc->sendCustomNotice($send);
		}
		if ( $forms['notice']['email'] ){
			$r = ihttp_email($forms['notice']['email'], $forms['notice']['title'], $forms['notice']['content']);
		}
	}
	die(json_encode( array('s'=>200, 'msg' => $forms['info'], 'redirect'=>$forms['redirect'])) );
	exit();
}

foreach ($attrs as $key => &$val) {
	$val['extra'] = explode("\r\n",$val['extra']);
}

$forms_status = '';

if( $forms['num']>0 ){
	$count = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('fwei_forms_fans').' WHERE openid = :openid AND rid=:rid', array(':openid'=>$openid,':rid'=>$rid) );
	if( $count>=$forms['num'] ){
		$forms_status = '您已超过最大参与次数限制！';
	}
}

if( $forms['max_num']>0 ){
	$count = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('fwei_forms_fans').' WHERE rid=:rid', array(':rid'=>$rid) );
	if( $count>=$forms['max_num'] ){
		$forms_status = '已超过最大参与次数限制！';
	}
}

if( TIMESTAMP < $forms['stime'] ){
	$forms_status = '您来得太早啦，开始时间为：'.date('Y-m-d H:i:s', $forms['stime']);
}
if( TIMESTAMP > $forms['etime'] ){
	$forms_status = '您来得太晚啦，结束时间为：'.date('Y-m-d H:i:s', $forms['etime']);
}

include $this->template('forms');