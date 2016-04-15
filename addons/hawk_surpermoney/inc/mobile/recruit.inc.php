<?php
global $_W,$_GPC;
$url = $this->createMobileUrl('family',array('fm'=>$_W['fans']['from_user']));
$url = substr($url,2);
$url = 'http://'.$_SERVER['HTTP_HOST'].'/app/'.$url;
$surl = $url;
$url = base64_encode($url);
include $this->template('recruit');