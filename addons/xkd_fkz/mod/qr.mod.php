<?php

class qr {
	private $t_qr;
	private $t_sys_qrcode;
	
	private $tb_qr;
	private $tb_sys_qrcode;
	

	private static $WECHAT_MEDIA_EXPIRE_SEC = 255600; // (3 * 24 * 60 * 60 - 1 * 60 * 60) seconds; 3 days
	
	public function __construct() {
		$this->t_qr = 'jiexi_aaa_qr';
		$this->t_sys_qrcode = 'qrcode';
		
		$this->tb_qr = tablename($this->t_qr);
		$this->tb_sys_qrcode = tablename($this->t_sys_qrcode);
	}

	public function get_qr($poster_id, $openid) {
		global $_W;
		
		$sql = "SELECT * FROM " . $this->tb_qr . " WHERE poster_id=:poster_id AND openid=:openid AND uniacid=:uniacid ORDER BY createtime DESC LIMIT 1";

		$pars = array();
		$pars[':poster_id'] = $poster_id;
		$pars[':openid'] = $openid;
		$pars[':uniacid'] = $_W['uniacid'];
			
		$qr = pdo_fetch($sql, $pars);
		
		if (!empty($qr) && ($qr['createtime'] + self::$WECHAT_MEDIA_EXPIRE_SEC	< time())) {
			unset($pars[':openid']);
			pdo_delete($this->t_qr, $pars);
			
			unset($qr);
			$qr = null;
		}
		return $qr;
	}
	
	public function get_newest_qr_by_openid($openid) {
		global $_W;
		
		$sql = "SELECT * FROM " . $this->tb_qr . " WHERE openid=:openid AND uniacid=:uniacid ORDER BY createtime DESC LIMIT 1";
		
		$pars = array();
		$pars[':openid'] = $openid;
		$pars[':uniacid'] = $_W['uniacid'];
			
		$qr = pdo_fetch($sql, $pars);
		
		if (!empty($qr) && ($qr['createtime'] + self::$WECHAT_MEDIA_EXPIRE_SEC	< time())) {
			unset($pars['openid']);
			pdo_delete($this->t_qr, $pars);
				
			unset($qr);
			$qr = null;
		}
		return $qr;
	}

	public function get_qr_by_scene($scene_id) {
		global $_W;
		
		$sql = "select * from " . $this->tb_qr . " where scene_id=:scene_id AND uniacid=:uniacid";
		
		$pars = array();
		$pars[':scene_id'] = $scene_id;
		$pars[':uniacid']= $_W['uniacid'];

		return pdo_fetch($sql, $pars);
	}

	public function add_qr($scene_id, $qr_url, $media_id, $poster_id, $openid) {
		global $_W;
		
		$qr = array();
		$qr['uniacid'] = $_W['weid'];
		$qr['openid'] = $openid;
		$qr['scene_id'] = $scene_id;
		$qr['qr_url'] = $qr_url;
		$qr['media_id'] = $media_id;
		$qr['poster_id'] = $poster_id;
		$qr['createtime'] = TIMESTAMP;
		
		$ret = pdo_insert($this->t_qr, $qr);
		
		$sys_qrcode = array();
		$sys_qrcode['uniacid'] = $_W['uniacid'];
		$sys_qrcode['acid'] = $_W['acid'];
		$sys_qrcode['qrcid'] = $scene_id;
		$sys_qrcode['model'] = 2;
		$sys_qrcode['name'] = $this->uid;
		$sys_qrcode['keyword'] = 'qr';
		$sys_qrcode['expire'] = 0;
		$sys_qrcode['createtime'] = TIMESTAMP;
		$sys_qrcode['status'] = 1;
		$sys_qrcode['ticket'] = $media_id;
		
		$ret = pdo_insert($this->t_sys_qrcode, $sys_qrcode);
		
		return $ret;
	}
}
?>