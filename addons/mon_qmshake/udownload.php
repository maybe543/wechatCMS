<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC,$_W;
$sid= intval($_GPC['sid']);
$shake = DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
$list = pdo_fetchall("SELECT * FROM " . tablename(DBUtil::$TABLE_QMSHAKE_USER) . " WHERE sid =:sid   ORDER BY createtime desc ", array(":sid"=>$sid));
$tableheader = array('openID', $this->encode("昵称"),$this->encode("姓名"),$this->encode("手机号"), $this->encode($shake['udefine']) , $this->encode('参与时间' ));
$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
	$html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
	$html .= $value['openid'] . "\t ,";
	 $html .= $this->encode( $value['nickname'] )  . "\t ,";
	$html .= $this->encode( $value['uname'] )  . "\t ,";
	$html .= $this->encode( $value['tel'] )  . "\t ,";
	$html .= $this->encode( $value['udefine'] )  . "\t ,";
	$html .= ($value['createtime'] == 0 ? '' : date('Y-m-d H:i',$value['createtime'])) . "\n";

}
header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参加用户数据.xls");
echo $html;
exit();
