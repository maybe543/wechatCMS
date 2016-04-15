<?php
/**
 * 老马群聊红包模块定义
 *
 * @author n1ce   QQ：541535641
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class N1ce_adredModule extends WeModule {
	public $table_reply = 'n1ce_adred_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W, $_GPC;
		load()->func('tpl');
		if($rid==0){
			$reply = array(
				'title'=> '双12红包群（20）',
				'description' => '马云 阿里邀请你加入了“亿万富豪红包群”群聊',
				'name'	=>  '杨晔 珠宝商',
				'head'=>  '../addons/n1ce_adred/template/style/images/tx3.jpg',
				'nullhb'	=>	'../addons/n1ce_adred/template/style/images/hb1_null.jpg',
				'hb'	=>	'../addons/n1ce_adred/template/style/images/hb2.jpg',
				'adhb'=>  '../addons/n1ce_adred/template/style/images/hb2_null.jpg',
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
				'title' => $_GPC['title'],
				'thumb' => $_GPC['thumb'],
				'description' => $_GPC['description'],
				'name' => $_GPC['name'],
				'head' => $_GPC['head'],
				'nullhb' => $_GPC['nullhb'],
				'hb' => $_GPC['hb'],
				'adhb' => $_GPC['adhb'],
				'share_title' => $_GPC['share_title'],
				'share_des' => $_GPC['share_des'],
				'share_img' => $_GPC['share_img'],
				'share_url' => $_GPC['share_url'],
				'createtime' => time(),		
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