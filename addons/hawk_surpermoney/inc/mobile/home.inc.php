<?php
global $_W,$_GPC;
$level = $_GPC['lv'];
load()->func('cache');
$key = 'surpermoney'.$_W['fans']['from_user'];
$meminfo = cache_read($key);
if($level==1){
    $data = $meminfo['first'];
}elseif($level==2){
    $data = $meminfo['second'];
}
include $this->template('home');