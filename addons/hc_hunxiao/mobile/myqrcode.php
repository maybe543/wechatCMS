<?php
	$profile = pdo_fetch('SELECT * FROM '.tablename('hc_hunxiao_member')." WHERE  weid = :weid  AND from_user = :from_user" , array(':weid' => $weid,':from_user' => $from_user));
	$id = $profile['id'];
	if($op=='qrcode'){
		load()->model('qrcode');
        $viewUrl = $_W['siteroot'].'app/'.$this->createMobileUrl('index',array('mid'=>$id));
        $url = urldecode($viewUrl);
        QRcode::png($url, false, 0, 8);
	}
	include $this->template('myqrcode');
?>