<?php
global $_W, $_GPC;
ignore_user_abort(true);

$this->loadMod('member');
$mod_member = new member();

load()->model('mc');

$uniacid = $_GPC['i'];
$openid = $_GPC['openid'];
$uid = mc_openid2uid($openid);

$member = $mod_member->get_member($uid);

if (empty($member)) {
	$member = array(
		'uid' => $uid,
		'uniacid' => $uniacid,
		'openid' => $openid,
		'level' => 0,
		'add_time' => TIMESTAMP
	);
	
	$mod_member->add_member($member);
}

$qr = new QRResponser();
$qr->respondText($openid);

exit(0);
?>