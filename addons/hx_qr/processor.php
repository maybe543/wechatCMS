<?php
/**
 * 加粉神器（扫码版）模块处理程序
 *
 * @author 华轩科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Hx_qrModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'hx_qr_reply';
	public function respond() {
		global $_W;
		load()->func('file');
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		$acc = WeAccount::create($_W['account']['acid']);
		if (!empty($rid)) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				$this->send($_W['account']['acid'],array('touser'=>$fromuser,'msgtype'=>'text','text'=>array('content'=>urlencode($reply['reply1']))));
				$fan = $this->faninfo($acc,$reply['id'],$fromuser);
				$qr = $this->createQr($_W['account']['acid'],$fan,$reply['keyword']);
				$bg_file = ATTACHMENT_ROOT.$this->setbg($reply['bg'],$fan);
				$qr_file = ATTACHMENT_ROOT.$qr;
				$avater_file = ATTACHMENT_ROOT.$fan['avater'];
				$target_file = ATTACHMENT_ROOT.'/images/'.$_W['uniacid'].'/hx_qr/img/'.$fan['id'].'.jpg';
				$this->mergeImage($bg_file, $qr_file, $target_file, array('left'=>$reply['qrleft'], 'top'=>$reply['qrtop'], 'width'=>$reply['qrwidth'], 'height'=>$reply['qrheight']));
				if ($reply['avatarwidth'] != 0 && $reply['avatarheight'] != 0) {
					$this->mergeImage($target_file, $avater_file, $target_file, array('left'=>$reply['avatarleft'], 'top'=>$reply['avatartop'], 'width'=>$reply['avatarwidth'], 'height'=>$reply['avatarheight']));
				}
				if ($reply['namesize'] != 0) {
					$this->writeText($target_file, $target_file, $fan['nickname'], array('size'=>$reply['namesize'], 'left'=>$reply['nameleft'], 'top'=>$reply['nametop']));
				}
				$media_id = $this->uploadimg($target_file);
				$send['touser'] = $fromuser;
				$send['msgtype'] = 'image';
				$send['image'] = array('media_id' => $media_id);
				$this->send($_W['account']['acid'],$send);
				return $this->respText($reply['reply2']);
			}
		}
		return null;
	}

	private function faninfo($acc,$reply_id,$from){//获取信息 headimgurl
		global $_W;
		load()->func('communication');
		load()->func('file');
		$info = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user')." WHERE reply_id='{$reply_id}' and openid='{$from}'");
		if (!empty($info['nickname'])) {
			return $info;
		}else{
			$fan = $acc->fansQueryInfo($from, true);
			$file = ihttp_get($fan['headimgurl']);
			$file = $file['content'];
			$avater = '/images/'.$_W['uniacid'].'/hx_qr/avater/'.$from.'.jpg';
			file_write($avater,$file);
			$insert = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['account']['acid'],
				'reply_id' => $reply_id,
				'openid' => $from,
				'nickname' => $fan['nickname'],
				'avater' => $avater,
				'status' => '1',
				'createtime' => TIMESTAMP,
				);
			pdo_insert('hx_qr_user',$insert);
			$info = pdo_fetch("SELECT * FROM ".tablename('hx_qr_user')." WHERE reply_id='{$reply_id}' and openid='{$from}'");
			return $info;
		}
	}

	private function setbg($bg,$fan){
		global $_W;
		load()->func('communication');
		load()->func('file');
		$bg = tomedia($bg);
		$file = ihttp_get($bg);
		$file = $file['content'];
		$img = '/images/'.$_W['uniacid'].'/hx_qr/img/'.$fan['id'].'.jpg';
		file_write($img,$file);
		return $img;
	}

	private function createQr($acid,$fan,$keyword){
		global $_W;
		if (file_exists(ATTACHMENT_ROOT.'/images/'.$_W['uniacid'].'/hx_qr/qr/'.$fan['openid'].'.jpg')) {
			return '/images/'.$_W['uniacid'].'/hx_qr/qr/'.$fan['openid'].'.jpg';
		}else{
			load()->func('communication');
			load()->func('file');
			$uniacccount = WeAccount::create($acid);
			$qrcid = pdo_fetchcolumn("SELECT qrcid FROM ".tablename('qrcode')." WHERE acid = :acid AND model = '2' ORDER BY qrcid DESC", array(':acid' => $acid));
			$barcode['action_info']['scene']['scene_id'] = !empty($qrcid) ? ($qrcid+1) : 1;
			if ($barcode['action_info']['scene']['scene_id'] > 100000) {
				return '抱歉，永久二维码已经生成最大数量，请先删除一些。';
			}
			$barcode['action_name'] = 'QR_LIMIT_SCENE';
			$result = $uniacccount->barCodeCreateFixed($barcode);
			if(!is_error($result)) {
				$insert = array(
					'uniacid' => $_W['uniacid'],
					'acid' => $acid,
					'qrcid' => $barcode['action_info']['scene']['scene_id'],
					'keyword' => $keyword,
					'name' => $fan['nickname']."的推广二维码",
					'model' => '2',
					'ticket' => $result['ticket'],
					'expire' => $result['expire_seconds'],
					'createtime' => TIMESTAMP,
					'status' => '1',
				);
				pdo_insert('qrcode', $insert);
				$qrid = pdo_insertid();
				pdo_update('hx_qr_user',array('qrid'=>$qrid),array('id'=>$fan['id']));
				$file = ihttp_get('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$result['ticket']);
				$file = $file['content'];
				$qr = '/images/'.$_W['uniacid'].'/hx_qr/qr/'.$fan['openid'].'.jpg';
				file_write($qr,$file);
				return $qr;
			} else {
				return $result['message'];
			}
		}
	}

	private function send($acid,$send){
		$acc = WeAccount::create($acid);
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
	private function uploadimg($media){
		$token = WeAccount::token(WeAccount::TYPE_WEIXIN);
		$sendapi = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
		$data = array(
			'media' => '@'.$media
		);
		
		load()->func('communication');
		$response = ihttp_request($sendapi, $data);
		$response = json_decode($response['content'], true);
		return $response['media_id'];
	}
	private function imagecreate($bg) {
		$bgImg = @imagecreatefromjpeg($bg);
		if (FALSE == $bgImg) {
			$bgImg = @imagecreatefrompng($bg);
		}
		if (FALSE == $bgImg) {
			$bgImg = @imagecreatefromgif($bg);
		}
		return $bgImg;
	}

	private function mergeImage($bg, $qr, $out, $param) {
		load()->func('file');
		list($bgWidth, $bgHeight) = getimagesize($bg);
		list($qrWidth, $qrHeight) = getimagesize($qr);
		extract($param);
		$bgImg = $this->imagecreate($bg);
		$qrImg = $this->imagecreate($qr);
		imagecopyresized($bgImg, $qrImg, $left, $top, 0, 0, $width, $height, $qrWidth, $qrHeight);
		ob_start();
		// output jpeg (or any other chosen) format & quality
		imagejpeg($bgImg, NULL, 100);
		$contents = ob_get_contents();
		ob_end_clean();
		imagedestroy($bgImg);
		imagedestroy($qrImg);
		$fh = fopen($out, "w+" );
		fwrite( $fh, $contents );
		fclose( $fh );
	}

	private function writeText($bg, $out, $text, $param = array()) {
		list($bgWidth, $bgHeight) = getimagesize($bg);
		extract($param);
		$im = imagecreatefromjpeg($bg);
		$black = imagecolorallocate($im, 0, 0, 0);
		$font = IA_ROOT . '/addons/hx_qr/template/style/fonts/msyhbd.ttf';
		//$text = 'hello';
		$white = imagecolorallocate($im, 255, 255, 255);
		imagettftext($im, $size, 0, $left, $top+$size/2, $white, $font, $text);
		ob_start();
		// output jpeg (or any other chosen) format & quality
		imagejpeg($im, NULL, 100);
		$contents = ob_get_contents();
		ob_end_clean();
		imagedestroy($im);
		$fh = fopen($out, "w+" );
		fwrite( $fh, $contents );
		fclose( $fh );
	}
}