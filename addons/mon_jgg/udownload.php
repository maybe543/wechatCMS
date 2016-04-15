<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC, $_W;
$jid = intval($_GPC['jid']);
if (empty($jid)) {
    message('抱歉，传递的参数错误！', '', 'error');
}



$list = pdo_fetchall("SELECT * FROM " . tablename(CRUD::$table_jgg_user) ." WHERE jid =:jid  ORDER BY createtime DESC", array(':jid'=>$jid));


$tableheader = array('openID', iconv("UTF-8", "GB2312", '昵称'), iconv("UTF-8", "GB2312", '手机号'), iconv("UTF-8", "GB2312", '用户名'), iconv("UTF-8", "GB2312", '注册时间'));


$html = "\xEF\xBB\xBF";
foreach ($tableheader as $value) {
    $html .= $value . "\t ,";
}
$html .= "\n";
foreach ($list as $value) {
    $html .= $value['openid'] . "\t ,";
    $html .= iconv("UTF-8", "GB2312", $value['nickname']) . "\t ,";
    $html .= iconv("UTF-8", "GB2312", $value['tel']) . "\t ,";
    $html .= iconv("UTF-8", "GB2312", $value['uname']) . "\t ,";



    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";




}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=参与用户数据.csv");

echo $html;
exit();
