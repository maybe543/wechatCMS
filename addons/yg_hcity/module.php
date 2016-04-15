<?php

defined('IN_IA') or exit('Access Denied');

class Yg_hcityModule extends WeModule {
	public $table_reply = 'yg_hcity_reply';
	public $table_oauth = 'yg_hcity_oauth';
	public function fieldsFormDisplay($rid = 0) {
		global $_W;
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		if ($rid == 0) {
			$reply = array(
				'title'=> '亲子游活动开始了!',
				'description' => '亲子游活动开始啦！',
				'starttime' => time(),
				'endtime' => time() + 10 * 84400,
				'status' => 1,
				'indexmsg' => '幸福一家',
				'pwd' => '0805',
				'lpyl1' => '你看还是好久带娃儿去耍一圈嘛，我们办公室同事的娃儿都去三亚了',
				'lzren' => '就是，趁现在还是放暑假带娃儿出去耍下嘛',
				'zmniang' => '张大妈的孙娃子都去香港迪士尼了，你们把娃儿带走了，我下礼拜也好约两场麻将',
				'lpyl2' => '路线我都看好了，等下发你，你先把机票，酒店那些订了',
				'myyl' => '好我马上订票',
				'zmniangname'=>'丈母娘',
				'lzrenname'=>'老丈人',
				'lpylname'=>'老婆',
				'actkh' => '亲子出游？钱不够？
                        <br />
                        华侨城·原岸2015百万旅行计划
                        <br />
                        免费送你去旅行',
				'actlink' => 'www.baidu.com',
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
		$i = 1;
		
		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'thumb' => $_GPC['thumb'],
			'zmniangname'=>$_GPC['zmniangname'],
			'lzrenname'=>$_GPC['lzrenname'],
			'lpylname'=>$_GPC['lpylname'],
			'description' => $_GPC['description'],
			'starttime' => strtotime($_GPC['time'][start]),
			'endtime' => strtotime($_GPC['time'][end]),
			'status' => intval($_GPC['status']),			
			'indexmsg' => $_GPC['indexmsg'],
			'pwd' => $_GPC['pwd'],
			'lpyl1' => $_GPC['lpyl1'],
			'lzren' => $_GPC['lzren'],
			'zmniang' => $_GPC['zmniang'],
			'lpylpic' => $_GPC['lpylpic'],
			'lzrenpic' => $_GPC['lzrenpic'],
			'zmniangpic' => $_GPC['zmniangpic'],
			'lpyl2' => $_GPC['lpyl2'],
			'myyl' => $_GPC['myyl'],
			'actkh' =>htmlspecialchars_decode($_GPC['actkh']),
			'actlink' => $_GPC['actlink'],
			'sharepic' =>$_GPC['sharepic'],
			'sharedesc' => $_GPC['sharedesc'],
			'sharetitle' => $_GPC['sharetitle'],
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


	public function settingsDisplay($settings) {
		
		global $_GPC, $_W;
		if(checksubmit()) {
			$cfg = array();
			$cfg['appid'] = $_GPC['appid'];
			$cfg['secret'] = $_GPC['secret'];
			if($this->saveSettings($cfg)) {

				$insert= array(
				'weid' => $_W['weid'],
				'appid'  => $cfg['appid'],
				'secret'  => $cfg['secret'],
				);
				$result= pdo_fetch("select * from ".tablename($this->table_oauth)." where 1=1 and weid={$_W['weid']}");
				if (empty($result)) {

					pdo_insert($this->table_oauth, $insert);
				} else {			
					pdo_update($this->table_oauth, $insert, array('id' => $result['id']));
				}
				message('保存成功', 'refresh');
			}
		}	
			$config = '已授权';
			
		include $this->template('setting');
	}
}