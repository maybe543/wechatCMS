<?php

class QRResponser {
	public function aa($openid, $uniacid, $id){
		global $_W;
		WeUtility::logging("来到responser文件", "sdfsdfsdf");
		$member = pdo_fetch("select * from ".tablename('hc_hunxiao_member')." where weid = ".$uniacid." and from_user = '".$openid."'");
		WeUtility::logging("北京图片1", $bg_file);
		$bg_file = pdo_fetchcolumn( " SELECT qrpicture FROM ".tablename('hc_hunxiao_rules')." WHERE weid=".$uniacid." " );
		$bg_file = $_W['attachurl'].$bg_file;
		WeUtility::logging("北京图片", $bg_file);

		if(empty($member['headimg'])){
			$hr_file = IA_ROOT.'/addons/hc_hunxiao/qrcode/header.png';
			//$hr_file = "https://ss3.baidu.com/-fo3dSag_xI4khGko9WTAnF6hhy/super/whfpf%3D425%2C260%2C50/sign=69bc3e8870c6a7efb973fb669bc79b63/730e0cf3d7ca7bcb84eb5c78b8096b63f724a8d1.jpg";
			WeUtility::logging("头像缺失，使用头像", $hr_file);
		} else {
			$hr_file = $member['headimg'];
			WeUtility::logging("头像存在", $hr_file);
		}
		$imgname = $weid."share$id.png";
		$qr_file = IA_ROOT."/addons/hc_hunxiao/style/images/share/$imgname";
		if(!file_exists($qr_file)){
			require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			$value = $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=hc_hunxiao&do=index&mid=' . $id;
			$errorCorrectionLevel = "L";
			$matrixPointSize = "4";
			QRcode::png($value, $qr_file, QR_ECLEVEL_H, $matrixPointSize);
		}
		$qr_file = $_W['siteroot']."/addons/hc_hunxiao/style/images/share/$imgname";
		$poster = pdo_fetch('SELECT * FROM ' . tablename('hc_hunxiao_poster') . ' WHERE uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
		$waittext = !empty($poster['waittext']) ? $poster['waittext'] : '您的专属海报正在拼命生成中，请等待片刻...';
		$this->sendText($openid, $waittext);
		set_time_limit(0);
		@ini_set('memory_limit', '256M');
		$target = imagecreatetruecolor(640, 1008);
		$resource_file = $this->create_image(tomedia($poster['bg']));
		imagecopy($target, $resource_file, 0, 0, 0, 0, 640, 1008);
		imagedestroy($resource_file);
		$alldata = json_decode(str_replace('&quot;', '\'', $poster['data']), true);
		
		foreach ($alldata as $dat) {
			$dat = $this->getRealData($dat);
			if ($dat['type'] == 'head') {
				$headimg = $member['headimg'];
				$target = $this->mergeImage($target, $dat, $headimg);
			} else {
				if ($dat['type'] == 'img') {
					$target = $this->mergeImage($target, $dat, $dat['src']);
				} else {
					if ($dat['type'] == 'qr') {
						$target = $this->mergeImage($target, $dat, tomedia($qr_file));
					} else {
						if ($dat['type'] == 'realname') {
							$target = $this->mergeText($target, $dat, $member['realname']);
						}
					}
				}
			}
		}
		$target_file = IA_ROOT.'/addons/hc_hunxiao/qrcode/qrshare/'.$uniacid."share$id.jpg";
		imagejpeg($target, $target_file);
        imagedestroy($target);
		if(file_exists($target_file)){
			$media_id = $this->uploadImage($target_file);
			if(!empty($media_id)){
				pdo_update('hc_hunxiao_member', array('media_id'=>$media_id, 'ischange'=>0, 'mediatime'=>time()), array('id'=>$id));
				$ret = $this->sendImage($openid, $media_id);
			}
		}	
	}
	
	public function getQR($scene_id) {
		$qr_url = null;
		$data = array("action_name" => "QR_LIMIT_SCENE", "action_info" => array("scene" => array("scene_id" => intval('1'.$scene_id))));
		$content = $this->getQRTicket($this->getAccessToken(), $data);
		if ($content['errcode'] == 0) {
			$qr_url = $this->getQRImage($content['ticket']);
		}
		return $qr_url;
	}
	
	public function getQRImage($ticket) {
		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
		return $url;
	}
	
	public function getQRTicket($token, $data) {
		load()->func('communication');
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
		$ret = ihttp_request($url, json_encode($data));
		$content = @json_decode($ret['content'], true);
		return $content;
	}
	
	public function uploadImage($img) {
		return $this->uploadRes($this->getAccessToken(), $img, 'image');
	}
	
	public function getAccessToken(){
		load()->model('account');
		$account = uni_fetch();
		load()->classs('weixin.account');
		$token = WeAccount::token();
		return $token;
	}

	public function uploadRes($access_token, $img, $type) {
		load()->func('communication');
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";
		//$img = substr(trim($img), 1);
		$post = array(
		  'media' => '@' . $img
		);
		$ret = ihttp_request($url, $post);
		$content = @json_decode($ret['content'], true);
		return $content['media_id'];
	}
	
	public function sendText($openid, $text) {
		$data = array(
			"touser"=>$openid,
			"msgtype"=>"text",
			"text"=>array("content"=>$text)
		);
		$json = $this->json_encode($data);
		$ret = $this->sendRes($this->getAccessToken(), $json);
		return $ret;
	}
	
	public function sendImage($openid, $media_id) {
		$data = array(
			"touser"=>$openid,
			"msgtype"=>"image",
			"image"=>array("media_id"=>$media_id)
		);
		$ret = $this->sendRes($this->getAccessToken(), json_encode($data));
		return $ret;
	}
	
	public static function arrayRecursive(&$array, $function, $apply_to_keys_also = false){
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
              self::arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
     
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }
	
    public static function json_encode($array) {
        self::arrayRecursive($array, 'urlencode', true);
        $json = json_encode($array);
        return urldecode($json);
    }
	
	public function sendRes($access_token, $data) {
		load()->func('communication');
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}
	
	public function imagecreate($bg) {
		$bgImg = @imagecreatefromjpeg($bg);
		if (FALSE == $bgImg) {
		  $bgImg = @imagecreatefrompng($bg);
		}
		if (FALSE == $bgImg) {
		  $bgImg = @imagecreatefromgif($bg);
		}
		return $bgImg;
	}
	
	public function mergeText($target, $alldata, $text){
		$font = IA_ROOT . '/addons/hc_hunxiao/style/font/qrcodefont/msyhbd.ttf';
		$color = $this->textrgb($alldata['color']);
		$colors = imagecolorallocate($target, $color['red'], $color['green'], $color['blue']);
		imagettftext($target, $alldata['size'], 0, $alldata['left'], $alldata['top'] + $alldata['size'], $colors, $font, $text);
		return $target;
	}
	
	public function textrgb($color){
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}
		if (strlen($color) == 6) {
			list($color1, $color2, $color3) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
		} elseif (strlen($color) == 3) {
			list($color1, $color2, $color3) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		} else {
			return false;
		}
		$color1 = hexdec($color1);
		$color2 = hexdec($color2);
		$color3 = hexdec($color3);
		return array('red' => $color1, 'green' => $color2, 'blue' => $color3);
	}
	
	public function createImage($imgurl){
		load()->func('communication');
		$resp = ihttp_request($imgurl);
		return imagecreatefromstring($resp['content']);
	}
	
	public function mergeImage($target, $data, $imgurl){
		$img = $this->createImage($imgurl);
		$w = imagesx($img);
		$h = imagesy($img);
		imagecopyresized($target, $img, $data['left'], $data['top'], 0, 0, $data['width'], $data['height'], $w, $h);
		imagedestroy($img);
		return $target;
	}
	
	function create_image($imgurl){
		$dll = strtolower(substr($imgurl, strrpos($imgurl, '.')));
		if ($dll == '.png') {
			$sourceurl = imagecreatefrompng($imgurl);
		} else {
			if ($dll == '.gif') {
				$sourceurl = imagecreatefromgif($imgurl);
			} else {
				$sourceurl = imagecreatefromjpeg($imgurl);
			}
		}
		return $sourceurl;
	}
	
	public function getRealData($alldata){
		$alldata['left'] = intval(str_replace('px', '', $alldata['left'])) * 2;
		$alldata['top'] = intval(str_replace('px', '', $alldata['top'])) * 2;
		$alldata['width'] = intval(str_replace('px', '', $alldata['width'])) * 2;
		$alldata['height'] = intval(str_replace('px', '', $alldata['height'])) * 2;
		$alldata['size'] = intval(str_replace('px', '', $alldata['size'])) * 2;
		$alldata['src'] = tomedia($alldata['src']);
		return $alldata;
	}
}
?>