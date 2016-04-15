<?php
/**
 * 官方示例模块订阅器
 *
 * @author 微赞团队
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_qrModuleReceiver extends WeModuleReceiver {
	public $table_reply = 'hx_qr_reply';
	public function receive() {
		global $_W;
		load()->model('mc');
		$fromuser = $this->message['from'];
		if ($this->message['msgtype'] == 'event') {

			if ($this->message['event'] == 'subscribe' && !empty($this->message['ticket'])) {
				$scene_id = str_replace('qrscene_', '',$this->message['eventkey']);
				$qrid = pdo_fetch("SELECT id FROM ".tablename('qrcode')." WHERE qrcid=:qrcid",array(':qrcid'=>$scene_id));
				$sub_user = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user')." WHERE qrid=:qrid",array(':qrid'=>$qrid['id']));
				//$this->sendText(WeAccount::create('3'),$fromuser,$sub_user['nickname']);
				if (!empty($sub_user)) {//通过推广二维码关注
					$acc = WeAccount::create($sub_user['acid']);
					$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :reply_id", array(':reply_id' => $sub_user['reply_id']));
					$info = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user')." WHERE openid=:openid",array(':openid'=>$fromuser));
					if (empty($info)) {
						$fan = $this->faninfo($acc,$sub_user['reply_id'],$fromuser);
						$insert = array(
							'uniacid' => $_W['uniacid'],
							'acid' => $sub_user['acid'],
							'reply_id' => $sub_user['reply_id'],
							'openid' => $fromuser,
							'nickname' => $fan['nickname'],
							'avater' => $fan['avater'],
							'sub_openid' => $sub_user['openid'],
							'status' => '1',
							'createtime' => TIMESTAMP,
							);
						pdo_insert('hx_qr_user',$insert);
						mc_credit_update(mc_openid2uid($fromuser),'credit2',$reply['newbie_credit'],array('1','关注平台获得积分'));
					}
					//给sub_user加积分 并通知他
					mc_credit_update(mc_openid2uid($sub_user['openid']),'credit2',$reply['click_credit'],array('1','推荐一级下线获得积分'));
					pdo_query("update " . tablename('hx_qr_user') . " set first_level=first_level+1 where id=:id", array(":id" => $sub_user['id']));
					$this->sendText($acc,$sub_user['openid'],'在您的帮助下，您的朋友' . $fan['nickname'] . '成为了我们的新会员，您也获得了相应奖励，请查收积分');
					//给sub_user的上一级加积分 并通知他
					if (!empty($sub_user['sub_openid'])) {
						mc_credit_update(mc_openid2uid($sub_user['sub_openid']),'credit2',$reply['sub_click_credit'],array('1','推荐二级下线获得积分'));
						pdo_query("update " . tablename('hx_qr_user') . " set secend_level=secend_level+1 where openid=:openid and reply_id=:reply_id", array(":openid" => $sub_user['sub_openid'],':reply_id'=>$sub_user['reply_id']));
						$this->sendText($acc,$sub_user['sub_openid'],'您的下线' . $sub_user['nickname'] . '又推荐了一个新的会员，您也得到了相应奖励，请查收积分');
					}
				}
				
			}
		}

	}
	private function faninfo($acc,$reply_id,$from){//获取信息 headimgurl
		global $_W;
		load()->func('communication');
		load()->func('file');
		$fan = $acc->fansQueryInfo($from, true);
		$file = ihttp_get($fan['headimgurl']);
		$file = $file['content'];
		$avater = '/images/'.$_W['uniacid'].'/hx_qr/avater/'.$from.'.jpg';
		file_write($avater,$file);
		$info = array(
			'nickname' => $fan['nickname'],
			'avater' => $avater,
			);
		return $info;
	}

	private function sendText($acc,$openid,$content){
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($content));
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
}