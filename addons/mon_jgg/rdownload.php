<?php
if (PHP_SAPI == 'cli')
    die('This example should only be run from a Web Browser');
global $_GPC, $_W;
$jid = intval($_GPC['jid']);
if (empty($jid)) {
    message('抱歉，传递的参数错误！', '', 'error');
}


        $where = '';
        $params = array(
            ':jid' => $jid
        );


        if ($_GPC['uid'] != '') {
            $where .= " and r.uid=:uid";
            $params[':uid'] = $_GPC['uid'];
        }


        $keyword = $_GPC['keywords'];


        if (!empty($keyword)) {
            $where .= ' and (u.nickname like :nickname) or (u.tel like :tel)';
            $params[':nickname'] = "%$keyword%";
            $params[':tel'] = "%$keyword%";
        }


    $list = pdo_fetchall("SELECT r.*,u.nickname,u.tel,u.uname FROM " . tablename(CRUD::$table_jgg_user_record) . "  r left join ".tablename(CRUD::$table_jgg_user)." u on r.uid=u.id WHERE r.jid =:jid  ".$where." ORDER BY  id DESC ",$params);


    $tableheader = array('openID', iconv("UTF-8", "GB2312", '昵称'), iconv("UTF-8", "GB2312", '手机号'),iconv("UTF-8", "GB2312", '用户名'), iconv("UTF-8", "GB2312", '奖品名称'),iconv("UTF-8", "GB2312", '奖品级别'), iconv("UTF-8", "GB2312", '抽奖时间'));


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

    if(!empty($value['award_name'])){
        $html .= iconv("UTF-8", "GB2312", $value['award_name']) . "\t ,";

    }else{
        $html .= iconv("UTF-8", "GB2312", '未中奖') . "\t ,";

    }

    if(!empty($value['award_level'])){
        $html .= iconv("UTF-8", "GB2312", $value['award_level']) . "\t ,";

    }else{
        $html .= iconv("UTF-8", "GB2312", '未中奖') . "\t ,";

    }


    $html .= date('Y-m-d H:i:s', $value['createtime']) . "\n";

}


header("Content-type:text/csv");
header("Content-Disposition:attachment; filename=抽奖记录数据.csv");

echo $html;
exit();
