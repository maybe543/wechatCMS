<?php
if(PHP_SAPI == 'cli') die('This example should only be run from a Web Browser');
global $_GPC, $_W;
$weid=$_W['uniacid'];
$list=pdo_fetchall("SELECT * FROM ".tablename("amouse_auction_member")." WHERE uniacid =:weid ", array(":weid"=>$weid));
$tableheader=array('openID', $this->encode("昵称"), $this->encode('会员积分'), $this->encode('地址'), $this->encode('注册时间'));

$html="\xEF\xBB\xBF";
foreach($tableheader as $value) {
    $html.=$value."\t ,";
}
$html.="\n";
foreach($list as $value) {
    $html.=$value['openid']."\t ,";
    $html.=$this->encode($value['nickname'])."\t ,";
    $html.=$this->encode($value['score'])."\t ,";
    $html.=$this->encode($value['address'])."\t ,";
    $html.=($value['createtime'] == 0 ? '' : date('Y-m-d H:i', $value['createtime']))."\n";
}

header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");
echo $html;
exit();
