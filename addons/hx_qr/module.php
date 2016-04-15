<?php
/**
 * 加粉神器（扫码版）模块定义
 *
 * @author 华轩科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_qrModule extends WeModule {
	public $table_reply = 'hx_qr_reply';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		if ($rid == 0) {
			$reply = array(
				'bg' => $_W['siteroot'].'/addons/hx_qr/template/style/img/bg.jpg',
				'qrleft'=> '20',
				'qrtop' => '615',
				'qrwidth' => '220',
				'qrheight' => '220',
				'avatarleft' => '270',
				'avatartop' => '650',
				'avatarwidth' => '90',
				'avatarheight' => '90',
				'nameleft' => '380',
				'nametop' => '710',
				'namesize' => '20',
				'reply1' => '正在为您生成图像文件，请稍候...',
				'reply2' => '您的图片已经生成',
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'bg' => $_GPC['bg'],
			'qrleft' => intval($_GPC['qrleft']),
			'qrtop' => intval($_GPC['qrtop']),
			'qrwidth' => intval($_GPC['qrwidth']),
			'qrheight' => intval($_GPC['qrheight']),
			'avatarleft' => intval($_GPC['avatarleft']),
			'avatartop' => intval($_GPC['avatartop']),
			'avatarwidth' => intval($_GPC['avatarwidth']),
			'avatarheight' => intval($_GPC['avatarheight']),
			'nameleft' => intval($_GPC['nameleft']),
			'nametop' => intval($_GPC['nametop']),
			'namesize' => intval($_GPC['namesize']),
			'newbie_credit' => intval($_GPC['newbie_credit']),
			'click_credit' => intval($_GPC['click_credit']),
			'sub_click_credit' => intval($_GPC['sub_click_credit']),
			'keyword' => $_GPC['keyword'],
			'reply1' => $_GPC['reply1'],
			'reply2' => $_GPC['reply2'],
			'createtime' => TIMESTAMP,
			);
		if (empty($id)) {
			pdo_insert($this->table_reply, $insert);
		} else {
			unset($insert['createtime']);
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		$replies = pdo_fetchall("SELECT id  FROM ".tablename($this->table_reply)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->table_reply, "id IN ('".implode("','", $deleteid)."')");
	}


}