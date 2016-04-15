<?php

require_once(APP_PHP . 'wechatutil.php');

class UserManager {
	private static $t_sys_mc_fans = 'mc_mapping_fans';
	private static $t_sys_mc_member = 'mc_members';
	private static $t_sys_qr = 'qrcode';

	private $uid = 0;

	function __construct($uid) {
		$this->uid = $uid;
	}

	public function getUserInfo($from_user) {
		$fans = WechatUtil::fans_search($from_user, array('nickname','avatar'));
		return $fans;
	}

	public function saveUserInfo($info) {
		if (!isset($info['subscribe']) || $info['subscribe'] != 1) {
			return;
		}
		WeUtility::logging('saveUserInfo', $info);
		$from_user = $info['openid'];
		load()->model('mc');
		$uid = mc_openid2uid($from_user);
		mc_update($uid,
			array('nickname'=>$info['nickname'],
			'gender'=>$info['sex'],
			'nationality'=>$info['country'],
			'resideprovince'=>$info['province'],
			'residecity'=>$info['city'],
			'avatar'=>$info['headimgurl']));
	}
}
