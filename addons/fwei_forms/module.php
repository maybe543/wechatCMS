<?php
/**
 * 通用表单模块定义
 *
 * @author fwei.net
 * @url http://www.fwei.net/
 */
defined('IN_IA') or exit('Access Denied');

class Fwei_formsModule extends WeModule {
	private $tb_forms = 'fwei_forms';
	private $tb_forms_attrs = 'fwei_forms_attrs';
	private $tb_forms_values = 'fwei_forms_values';
	private $tb_forms_fans = 'fwei_forms_fans';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		$uniacid = $_W['uniacid'];
		$notice = array(
				'openid'	=>	'',
				'email'	=>	'',
				'title'	=>	'',
				'content'	=>	'',
		);
		if (!empty($rid)) {
			$item = pdo_fetch("SELECT * FROM ".tablename( $this->tb_forms )." WHERE rid = :rid", array(':rid' => $rid));
			$item['stime'] = date('Y-m-d H:i:s', $item['stime']);
			$item['etime'] = date('Y-m-d H:i:s', $item['etime']);
			$notice = $item['notice'] ? unserialize( $item['notice'] ) : $notice;
		} else {
			$item = array(
				'num'	=>	0,
				'max_num'	=>	0,
				'stime'	=>	date('Y-m-d H:i:s', TIMESTAMP),
				'etime'	=>	date('Y-m-d H:i:s', strtotime('+30 day', TIMESTAMP) ),
				'info'	=>	'提交成功，谢谢参与！',
				'show_desc'	=>	0,
			);
			
		}
		// 调用模板页面
		load()->func('tpl');
		include $this->template('rule');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		if ( empty($_GPC['title']) ) {
			return '请填写表单标题！';
		}
		if( empty($_GPC['thumb']) ){
			return '设置一张封面图片吧！';
		}
		if( $_GPC['stime'] >= $_GPC['etime'] ){
			return '起始时间设置有问题！';
		}
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$uniacid = $_W['uniacid'];

		$insert_data = array(
			'title'	=>	$_GPC['title'],
			'thumb'	=>	$_GPC['thumb'],
			'description'	=>	$_GPC['description'],
			'content'	=>	htmlspecialchars_decode($_GPC['content']),
			'stime'	=>	strtotime($_GPC['stime']),
			'etime'	=>	strtotime($_GPC['etime']),
			'info'	=>	$_GPC['info'],
			'num'	=>	$_GPC['num'],
			'max_num'	=>	$_GPC['max_num'],
			'show_desc'	=>	$_GPC['show_desc'] ? 1 : 0,
			'credit'	=>	$_GPC['credit'],
			'coupon'	=>	$_GPC['coupon'],
			'notice' => array(),
			'redirect'	=>	$_GPC['redirect'],
		);
		$insert_data['notice'] = array(
			'openid'	=>	$_GPC['notice_openid'],
			'email'	=>	$_GPC['notice_email'],
			'title'	=>	$_GPC['notice_title'],
			'content'	=>	$_GPC['notice_content'],
		);
		$insert_data['notice'] = serialize( $insert_data['notice'] );
		$sinfo = pdo_fetch( 'SELECT * FROM '.tablename($this->tb_forms).' WHERE uniacid = :uniacid AND rid = :rid' , array(':uniacid' => $uniacid,':rid'=>$rid));
		if( $sinfo ){
			pdo_update($this->tb_forms, $insert_data, array('rid'=>$rid));
		} else {
			$insert_data['rid']	=	$rid;
			$insert_data['uniacid']	=	$uniacid;
			$insert_data['timeline']	=	TIMESTAMP;

			pdo_insert($this->tb_forms, $insert_data);
		}

		message('表单信息保存成功！正转向表单字段管理！', $this->createWebUrl('attributes', array('id' => $rid)));
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		pdo_delete( $this->tb_forms, array('rid'=>$rid));
		pdo_delete($this->tb_forms_attrs, array('rid'=>$rid));
		pdo_delete($this->tb_forms_values, array('rid'=>$rid));
		pdo_delete($this->tb_forms_fans, array('rid'=>$rid));
	}


}