<?php
global $_W, $_GPC;
// load()->model('account');
// echo $this->account_types;
$uniacid = $_W['uniacid'];



//SELECT sex,sum(age) FROM `msg_info` group by sex
//$userlog=pdo_fetchall("select sum(money) from ".tablename('enjoy_red_log')." group by openid where uniacid=".$uniacid."");
$pindex = max(1, intval($_GPC['page']));
$psize  = 10;
if ($_GPC['nickname']) {
    $where = "and a.nickname LIKE '%" . $_GPC['nickname'] . "%'";
} else {
    $where = "";
}
$totals = pdo_fetchall("SELECT COUNT(*) as count from " . tablename('enjoy_red_log') . " where uniacid=" . $uniacid . " group by openid");
$total  = count($totals);



//echo "SELECT COUNT(*) from ".tablename('enjoy_red_log')." where uniacid=".$uniacid." group by openid";
// var_dump($total);
// exit();
$userlist = pdo_fetchall("select a.*,abs(SUM(b.money)) as sum,SUM(ABS(b.money)) as txsum from " . tablename('enjoy_red_fans') . " as a left join " . tablename('enjoy_red_log') . " as b on a.openid=b.openid
		where b.uniacid=" . $uniacid . " and a.uniacid=" . $uniacid . " " . $where . " group by b.openid order by SUM(b.money) desc LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
$pager    = pagination($total, $pindex, $psize);
$countadd = pdo_fetchcolumn("select count(*) from " . tablename('enjoy_red_fans') . " where uniacid=" . $uniacid . "");
$countsum = pdo_fetchcolumn("select abs(sum(money)) from " . tablename('enjoy_red_log') . " where uniacid=" . $uniacid . " and money<0");

if ($_GPC['op'] == 'excel') {
    $userlist1   = pdo_fetchall("select a.*,abs(SUM(b.money)) as sum,SUM(ABS(b.money)) as txsum from " . tablename('enjoy_red_fans') . " as a left join " . tablename('enjoy_red_log') . " as b on a.openid=b.openid
		where b.uniacid=" . $uniacid . " and a.uniacid=" . $uniacid . " " . $where . " group by b.openid order by SUM(b.money) desc");
    $title       = array(
        '昵称',
        '可提现',
        '已提现'
    );
    $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $title));
    
    $value['nickname'] = empty($value['nickname']) ? '匿名' : $value['nickname'];
    foreach ($userlist1 as &$value) {
        $tmp_value   = array(
            $value['nickname'],
            $value['sum'],
            ($value['txsum'] - $value['sum']) / 2
        );
        $arraydata[] = iconv("UTF-8", "GB2312//IGNORE", implode("\t", $tmp_value));
    }
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/vnd.ms-execl");
    header("Content-Type: application/force-download");
    header("Content-Type: application/download");
    header("Content-Disposition: attachment; filename=" . date('Ymd') . '.xls');
    header("Content-Transfer-Encoding: binary");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo implode("\t\n", $arraydata);
    exit();
}






















include $this->template('log');