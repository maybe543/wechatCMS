<?php
/**
 * 捷讯活动平台模块定义
 *
 * @author 捷讯设计
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class J_activityModule extends WeModule {
	public $tablename = 'j_activity_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		global $_W;
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->tablename)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}else{
			$reply=array(
				'joinstarttime'=>strtotime(date("Y-m-d")),
				'joinendtime'=>strtotime(date("Y-m-d")),
				'starttime'=>strtotime(date("Y-m-d")),
				'endtime'=>strtotime(date("Y-m-d")),
			);
		}
		$children = array();
		$category = pdo_fetchall("SELECT * FROM ".tablename('j_activity_category')." WHERE weid = '{$_W['uniacid']}' order by parentid asc, displayorder asc");
		foreach ($category as $index => $row) {
			if (!empty($row['parentid'])){
				$children[$row['parentid']][] = $row;
				unset($category[$index]);
			}
		}
		load()->func('tpl');
		$grouplist= pdo_fetchall("SELECT * FROM ".tablename("mc_groups")." WHERE uniacid = '".$_W['uniacid']."' ORDER BY `orderlist` asc");
		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return true;
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_GPC, $_W;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'weid'=> $_W['uniacid'],
			'picture' => $_GPC['picture'],
			'qrcode' => $_GPC['qrcode'],
			'pcate' => intval($_GPC['pcate']),
			'ccate' => intval($_GPC['ccate']),
			'clientpic' => $_GPC['clientpic'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'info' => htmlspecialchars_decode($_GPC['info']),
			'rule' => htmlspecialchars_decode($_GPC['rule']),
			'content' => htmlspecialchars_decode($_GPC['content']),
			'appendcode' => $_GPC['appendcode'],
			'quota' => intval($_GPC['quota']),
			'joinstarttime' => strtotime($_GPC['jointime']['start']),
			'joinendtime' => strtotime($_GPC['jointime']['end']),
			'starttime' => strtotime($_GPC['acttime']['start']),
			'endtime' => strtotime($_GPC['acttime']['end']),
			'applicants'=>intval($_GPC['applicants']),
			'status'=>intval($_GPC['status']),
			'usertype'=>intval($_GPC['usertype']),
			'credit_join'=>intval($_GPC['credit_join']),
			'credit_in'=>intval($_GPC['credit_in']),
			'credit_append'=>intval($_GPC['credit_append']),
			'longitude' => $_GPC['longitude'],
			'latitude' => $_GPC['latitude'],
			'address' => $_GPC['address'],
			'redirecturl' => $_GPC['redirecturl'],
			'organizer' => $_GPC['organizer'],
			'charge' => floatval($_GPC['charge']),
			'redirectmsg' => $_GPC['redirectmsg'],
		);
		$parama=array();
		if(isset($_GPC['parama-key'])){
			foreach ($_GPC['parama-key'] as $index => $row) {
				if(empty($row))continue;
				
				$parama[urlencode($row)]=urlencode($_GPC['parama-val'][$index]);
			}
		}
		if(isset($_GPC['parama-key-new'])){
			foreach ($_GPC['parama-key-new'] as $index => $row) {
				if(empty($row))continue;
				echo $_GPC['parama-val'][$index];
				$parama[urlencode($row)]=urlencode($_GPC['parama-val-new'][$index]);
			}
		}
		$insert['parama']=urldecode(json_encode($parama));
		if (empty($id)) {
			$insert['status']=1;
			pdo_insert($this->tablename, $insert);
		} else {
			pdo_update($this->tablename, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		global $_W;
		$replies = pdo_fetch("SELECT id, picture,qrcode,clientpic FROM ".tablename($this->tablename)." WHERE rid = '$rid'");
		load()->func('file');
		if (!empty($replies)) {
			file_delete($row['picture']);
			file_delete($row['qrcode']);
			file_delete($row['clientpic']);
			$deleteid[] = $row['id'];
			pdo_delete("j_activity_winner",array('aid'=>$row['id']));
			pdo_delete("j_activity_record",array('aid'=>$row['id']));
		}
		pdo_delete($this->tablename, array('id'=>$replies['id']));
		//message('删除成功', '', 'success');
		return true;
	}
	
	public function settingsDisplay($settings) {
        global $_GPC, $_W;
        if (checksubmit()) {
            $cfg = array(
				'share_title' => $_GPC['share_title'],
				'share_info' => $_GPC['share_info'],
				'share_img' => $_GPC['share_img'],
				
				
				'self_list' => $_GPC['self_list'],
				'self_view' => $_GPC['self_view'],
				
                'iscredit' => intval($_GPC['iscredit']),
				'showloading' => intval($_GPC['showloading']),
				'lisstyle' => $_GPC['lisstyle'],
				'viewstyle' => $_GPC['viewstyle'],
				'tempmsg_join_call' => $_GPC['tempmsg_join_call'],
				
				'tempmsg_on' => $_GPC['tempmsg_on'],
				'tempmsg_join_ok' => $_GPC['tempmsg_join_ok'],
				'tempmsg_join_out' => $_GPC['tempmsg_join_out'],
				'tempmsg_join_sign' => $_GPC['tempmsg_join_sign'],
				
				'is_pay'=> intval($_GPC['is_pay']),
				'user_oauth' => intval($_GPC['user_oauth']),
				'appsecret' => $_GPC['appsecret'],
				'appid' => $_GPC['appid'],
				
				'msg_join' => htmlspecialchars_decode($_GPC['msg_join']),
				'msg_ok' => htmlspecialchars_decode($_GPC['msg_ok']),
				'msg_false' => htmlspecialchars_decode($_GPC['msg_false']),
				'msg_attend' => htmlspecialchars_decode($_GPC['msg_attend']),
				'msg_temp1' => htmlspecialchars_decode($_GPC['msg_temp1']),
				'msg_temp2' => htmlspecialchars_decode($_GPC['msg_temp2']),
				'msg_temp3' => htmlspecialchars_decode($_GPC['msg_temp3']),
				'msg_temp4' => htmlspecialchars_decode($_GPC['msg_temp4']),
				
				'tempmsg_lucky' => $_GPC['tempmsg_lucky'],
				'msg_lucky' => $_GPC['msg_lucky'],
            );
            if ($this->saveSettings($cfg)) {
                message('保存成功', 'refresh');
            }
        }
		load()->func('tpl');
		include $this->template('setting');
    }
}