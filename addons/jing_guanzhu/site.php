<?php
defined('IN_IA') or exit('Access Denied');
define('CSS_PATH', '../addons/jing_guanzhu/template/style/css/');
define('JS_PATH', '../addons/jing_guanzhu/template/style/js/');
define('IMG_PATH', '../addons/jing_guanzhu/template/style/images/');
class Jing_guanzhuModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$name = $_GPC['name'];
		$share_title = isset($this->module['config']['share_title']) ? $this->module['config']['share_title'] : '这个公众号好强大，快来看看';
		$share_content = isset($this->module['config']['share_content']) ? $this->module['config']['share_content'] : '公众号越来越多，这样高品质的号越加稀少了，赶紧关注起来';
		if (empty($name)) {
			include $this->template('index');
		}else{
			include $this->template('show');
		}
	}

	public function doMobilecheckQrCode() {
		//这个操作被定义用来呈现 功能封面
		global $_W,$_GPC;
		$name = $_GPC['name'];
		load()->func('communication');
		$imgdata = ihttp_get("http://open.weixin.qq.com/qr/code/?username=".$name);
		if (!empty($imgdata['content'])) {
			$array = array(
				'base_resp' => array('ret' => 0),
				);
		}else{
			$array = array(
				'base_resp' => array('ret' => 1),
				);
		}
		exit(json_encode($array));
	}

	public function doMobileImg(){
		global $_W,$_GPC;
		load()->func('communication');
		$name = $_GPC['name'];
		$imgdata = ihttp_get("http://open.weixin.qq.com/qr/code/?username=".$name);
		$this->mergeImage($bg_file, $qr_file, $target_file, array('left'=>$reply['qrleft'], 'top'=>$reply['qrtop'], 'width'=>$reply['qrwidth'], 'height'=>$reply['qrheight']));
		exit($imgdata['content']);
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

}