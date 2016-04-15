<?php
error_reporting(0);
function getTopDomainhuo()
{
    $host = $_SERVER['HTTP_HOST'];
    $host = strtolower($host);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host  = $parse['host'];
    }
    $topleveldomaindb = array(
        'com',
        'edu',
        'gov',
        'int',
        'mil',
        'net',
        'org',
        'biz',
        'info',
        'top',
        'wang',
        'pro',
        'name',
        'museum',
        'coop',
        'aero',
        'xxx',
        'idv',
        'mobi',
        'cc',
        'me',
        'co'
    );
    $str              = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }
    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}
$domain      = getTopDomainhuo();

include 'plugin/yunzhixun.php';
defined('IN_IA') or exit('Access Denied');
class dayu_yuyuepayModuleSite extends WeModuleSite
{
    public function getHomeTiles()
    {
        global $_W;
        $urls = array();
        $list = pdo_fetchall("SELECT title, reid FROM " . tablename('dayu_yuyuepay') . " WHERE weid = '{$_W['uniacid']}'");
        if (!empty($list)) {
            foreach ($list as $row) {
                $urls[] = array(
                    'title' => $row['title'],
                    'url' => $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_yuyuepay', array(
                        'id' => $row['reid']
                    ))
                );
            }
        }
        return $urls;
    }
    public function doWebQuery()
    {
        global $_W, $_GPC;
        $kwd              = $_GPC['keyword'];
        $sql              = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `title` LIKE :title ORDER BY reid DESC LIMIT 0,8';
        $params           = array();
        $params[':weid']  = $_W['uniacid'];
        $params[':title'] = "%{$kwd}%";
        $ds               = pdo_fetchall($sql, $params);
        foreach ($ds as &$row) {
            $r                = array();
            $r['title']       = $row['title'];
            $r['description'] = cutstr(strip_tags($row['description']), 50);
            $r['thumb']       = $row['thumb'];
            $r['reid']        = $row['reid'];
            $row['entry']     = $r;
        }
        include $this->template('query');
    }
    public function doWebDetail()
    {
        global $_W, $_GPC;
        $rerid            = intval($_GPC['id']);
        $sql              = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE `rerid`=:rerid";
        $params           = array();
        $params[':rerid'] = $rerid;
        $row              = pdo_fetch($sql, $params);
        if (empty($row)) {
            message('访问非法.');
        }
        $hexiao          = "dayu_yuyuepay_shareQrcode" . $_W['uniacid'];
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $row['reid'];
        $activity        = pdo_fetch($sql, $params);
        $xm              = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE weid = :weid and reid = :reid and id = :xmid", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $row['reid'],
            ':xmid' => $row['xmid']
        ));
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params          = array();
        $params[':reid'] = $row['reid'];
        $fields          = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = $fids = array();
        foreach ($fields as $f) {
            $ds[$f['refid']]['fid']   = $f['title'];
            $ds[$f['refid']]['type']  = $f['type'];
            $ds[$f['refid']]['refid'] = $f['refid'];
            $fids[]                   = $f['refid'];
        }
        $record           = array();
        $record['status'] = intval($_GPC['status']);
        if (!empty($_GPC['paystatus'])) {
            $record['paystatus'] = intval($_GPC['paystatus']);
        }
        $record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
        $record['kfinfo']    = $_GPC['kfinfo'];
        if ($_GPC['status'] == '0') {
            $huifu = '等待客服确认：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '1') {
            $huifu = '客服已确认：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '2') {
            $huifu = '客服已拒绝：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '3') {
            $huifu = '服务完成';
        }
        $ymember  = $row['member'];
        $ymobile  = $row['mobile'];
        $yxiangmu = $this->get_xiangmu($row['reid'], $row['xmid']);
        $ytime    = date('Y-m-d H:i:s', $row['yuyuetime']);
        if (!empty($activity['mfirst'])) {
            $mfirst = $activity['mfirst'];
        } else {
            $mfirst = "预约结果通知";
        }
        if (!empty($activity['mfoot'])) {
            $mfoot = $activity['mfoot'];
        } else {
            $mfoot = "如有疑问，请致电联系我们。";
        }
        $template = array(
            "touser" => $row['openid'],
            "template_id" => $activity['m_templateid'],
            "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_yuyuepay', array(
                'name' => 'dayu_yuyuepay',
                'weid' => $row['weid'],
                'id' => $row['reid']
            )),
            "topcolor" => "#FF0000",
            "data" => array(
                'first' => array(
                    'value' => urlencode($mfirst),
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => urlencode($ymember),
                    'color' => '#000000'
                ),
                'keyword2' => array(
                    'value' => urlencode($yxiangmu['title'] . " - " . $yxiangmu['price'] . "元"),
                    'color' => '#000000'
                ),
                'keyword3' => array(
                    'value' => urlencode($_GPC['yuyuetime']),
                    'color' => '#000000'
                ),
                'keyword4' => array(
                    'value' => urlencode($huifu),
                    'color' => "#FF0000"
                ),
                'remark' => array(
                    'value' => urlencode($mfoot),
                    'color' => "#008000"
                )
            )
        );
        if ($_W['ispost']) {
            include "plugin/phpqrcode.php";
            $value                = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageyuyues', array(
                'name' => 'dayu_yuyuepay',
                'op' => 'detail',
                'id' => $rerid
            ));
            $errorCorrectionLevel = "L";
            $matrixPointSize      = "4";
            $imgname              = "hexiao$rerid.png";
            $imgurl               = "../addons/dayu_yuyuepay/hexiao/$imgname";
            QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
            load()->func('communication');
            $this->send_template_message(urldecode(json_encode($template)));
            pdo_update('dayu_yuyuepay_info', $record, array(
                'rerid' => $rerid
            ));
            message('修改成功', referer(), 'success');
        }
        $row['yuyuetime'] && $row['yuyuetime'] = date('Y-m-d H:i:s', $row['yuyuetime']);
        $fids          = implode(',', $fids);
        $row['fields'] = array();
        $sql           = 'SELECT * FROM ' . tablename('dayu_yuyuepay_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
        $fdatas        = pdo_fetchall($sql, $params);
        foreach ($fdatas as $fd) {
            $row['fields'][$fd['refid']] = $fd['data'];
        }
        foreach ($ds as $value) {
            if ($value['type'] == 'reside') {
                $row['fields'][$value['refid']] = '';
                foreach ($fdatas as $fdata) {
                    if ($fdata['refid'] == $value['refid']) {
                        $row['fields'][$value['refid']] .= $fdata['data'];
                    }
                }
                break;
            }
        }
        load()->func('tpl');
        include $this->template('detail');
    }
    public function doWebManage()
    {
        global $_W, $_GPC;
        $_accounts = $accounts = uni_accounts();
        load()->model('mc');
        if (empty($accounts) || !is_array($accounts) || count($accounts) == 0) {
            message('请指定公众号');
        }
        if (!isset($_GPC['acid'])) {
            $account = array_shift($_accounts);
            if ($account !== false) {
                $acid = intval($account['acid']);
            }
        } else {
            $acid = intval($_GPC['acid']);
            if (!empty($acid) && !empty($accounts[$acid])) {
                $account = $accounts[$acid];
            }
        }
        reset($accounts);
        $reid            = intval($_GPC['id']);
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity        = pdo_fetch($sql, $params);
        if (empty($activity)) {
            message('非法访问.');
        }
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
        $params          = array();
        $params[':reid'] = $reid;
        $fields          = pdo_fetchall($sql, $params);
        if (empty($fields)) {
            message('非法访问.');
        }
        $ds = array();
        foreach ($fields as $f) {
            $ds[$f['refid']] = $f['title'];
        }
        $starttime = empty($_GPC['daterange']['start']) ? strtotime('-1 month') : strtotime($_GPC['daterange']['start']);
        $endtime   = empty($_GPC['daterange']['end']) ? TIMESTAMP : strtotime($_GPC['daterange']['end']) + 86399;
        $select    = array();
        if (!empty($_GPC['select'])) {
            foreach ($_GPC['select'] as $field) {
                if (isset($ds[$field])) {
                    $select[] = $field;
                }
            }
        }
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $status    = $_GPC['status'];
        $paystatus = $_GPC['paystatus'];
        $where .= 'reid=:reid';
        $params          = array();
        $params[':reid'] = $reid;
        if (!empty($_GPC['time'])) {
            $starttime = strtotime($_GPC['time']['start']);
            $endtime   = strtotime($_GPC['time']['end']) + 86399;
            $where .= " AND createtime >= :starttime AND createtime <= :endtime ";
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }
        if (!empty($_GPC['keywords'])) {
            $where .= ' and (member like :member or mobile like :mobile)';
            $params[':member'] = "%{$_GPC['keywords']}%";
            $params[':mobile'] = "%{$_GPC['keywords']}%";
        }
        if (!empty($_GPC['orderid'])) {
            $where .= ' and (ordersn like :ordersn or transid like :transid)';
            $params[':ordersn'] = "%{$_GPC['orderid']}%";
            $params[':transid'] = "%{$_GPC['orderid']}%";
        }
        if ($status != '') {
            if ($status == 2) {
                $where .= " and ( status=2 or status=-1 )";
            } else {
                $where .= " and status='{$status}'";
            }
        }
        if ($paystatus != '') {
            $where .= " and paystatus='{$paystatus}'";
        }
        $sql   = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE $where ORDER BY createtime DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $list  = pdo_fetchall($sql, $params);
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE $where", $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as $index => $row) {
            $list[$index]['user'] = mc_fansinfo($row['openid'], $acid, $_W['uniacid']);
        }
        $order_count_all     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}'");
        $order_count_confirm = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}' AND status=0 AND paystatus=1");
        $order_count_pay     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}' AND status=0 AND paystatus=2");
        $order_count_finish  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}' AND status=1 AND paystatus=2");
        $order_count_cancel  = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}' AND ( status=2 or status=-1 )");
        $order_count_end     = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . "  WHERE reid = '{$reid}' AND status=3");
        foreach ($list as $key => &$value) {
            if (is_array($value['fields'])) {
                foreach ($value['fields'] as &$v) {
                    $img = '<div align="center"><img src="';
                    if (substr($v, 0, 6) == 'images') {
                        $v = $img . $_W['attachurl'] . $v . '" style="width:50px;height:50px;"/></div>';
                    }
                }
                unset($v);
            }
        }
        if (checksubmit('export', 1)) {
            $sql             = 'SELECT title FROM ' . tablename('dayu_yuyuepay_fields') . " AS f JOIN " . tablename('dayu_yuyuepay_info') . " AS r ON f.reid='{$params[':reid']}' GROUP BY title ORDER BY refid";
            $tableheader     = pdo_fetchall($sql, $params);
            $tablelength     = count($tableheader);
            $tableheaders[]  = array(
                'title' => '姓名'
            );
            $tableheaders[]  = array(
                'title' => '手机'
            );
            $tableheaders[]  = array(
                'title' => '预约项目'
            );
            $tableheaders[]  = array(
                'title' => '付款状态'
            );
            $tableheaders[]  = array(
                'title' => '预约状态'
            );
            $tableheaders[]  = array(
                'title' => '预约时间'
            );
            $tableheader[]   = array(
                'title' => '提交时间'
            );
            $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE `reid`=:reid AND `createtime` > {$starttime} AND `createtime` < {$endtime} ORDER BY `createtime` DESC";
            $params          = array();
            $params[':reid'] = $reid;
            $list            = pdo_fetchall($sql, $params);
            if (empty($list)) {
                message('暂时没有预约数据');
            }
            foreach ($list as &$r) {
                $r['fields'] = array();
                $sql         = 'SELECT data, refid FROM ' . tablename('dayu_yuyuepay_data') . " WHERE `reid`=:reid AND `rerid`='{$r['rerid']}' ORDER BY redid";
                $fdatas      = pdo_fetchall($sql, $params);
                foreach ($fdatas as $fd) {
                    if (false == array_key_exists($fd['refid'], $r['fields'])) {
                        $r['fields'][$fd['refid']] = $fd['data'];
                    } else {
                        $r['fields'][$fd['refid']] .= '-' . $fd['data'];
                    }
                }
            }
            $data = array();
            foreach ($list as $key => $value) {
                $data[$key]['member']    = $value['member'];
                $data[$key]['mobile']    = $value['mobile'];
                $data[$key]['xmid']      = $this->get_xiangmu($value['reid'], $value['xmid']);
                $data[$key]['paystatus'] = $value['paystatus'];
                $data[$key]['status']    = $value['status'];
                $data[$key]['yuyuetime'] = date('Y-m-d H:i:s', $value['yuyuetime']);
                if (!empty($value['fields'])) {
                    foreach ($value['fields'] as $field) {
                        if (substr($field, 0, 6) == 'images') {
                            $data[$key][] = str_replace(array(
                                "\n",
                                "\r",
                                "\t"
                            ), '', $_W['attachurl'] . $field);
                        } else {
                            $data[$key][] = str_replace(array(
                                "\n",
                                "\r",
                                "\t"
                            ), '', $field);
                        }
                    }
                }
                $data[$key]['createtime'] = date('Y-m-d H:i:s', $value['createtime']);
            }
            $html = "\xEF\xBB\xBF";
            foreach ($tableheaders as $value) {
                $html .= $value['title'] . "\t ,";
            }
            foreach ($tableheader as $value) {
                $html .= "\t ,";
            }
            $html .= "\n";
            foreach ($data as $value) {
                if ($value['paystatus'] == 1) {
                    $paystatus = '未付款';
                } elseif ($value['paystatus'] == 2) {
                    $paystatus = '已付款';
                }
                if ($value['status'] == '0') {
                    $huifu = '等待确认';
                } elseif ($value['status'] == '1') {
                    $huifu = '已确认';
                } elseif ($value['status'] == '2') {
                    $huifu = '已拒绝';
                } elseif ($value['status'] == '3') {
                    $huifu = '已完成';
                } elseif ($value['status'] == '-1') {
                    $huifu = '客户取消';
                }
                $html .= $value['member'] . "\t ,";
                $html .= $value['mobile'] . "\t ,";
                $html .= $value['xmid']['title'] . "\t ,";
                $html .= $paystatus . "\t ,";
                $html .= $huifu . "\t ,";
                $html .= $value['yuyuetime'] . "\t ,";
                for ($i = 0; $i < $tablelength; $i++) {
                    $html .= $value[$i] . "\t ,";
                }
                $html .= $value['createtime'] . "\t ,";
                $html .= "\n";
            }
            $stime = date('Ymd', $starttime);
            $etime = date('Ymd', $endtime);
            header('Content-type:text/csv');
            header("Content-Disposition:attachment; filename=预约数据$stime-$etime.csv");
            echo $html;
            exit();
        }
        include $this->template('manage');
    }
    public function doWebDisplay()
    {
        global $_W, $_GPC;
        if ($_W['ispost']) {
            $reid              = intval($_GPC['reid']);
            $switch            = intval($_GPC['switch']);
            $sql               = 'UPDATE ' . tablename('dayu_yuyuepay') . ' SET `status`=:status WHERE `reid`=:reid';
            $params            = array();
            $params[':status'] = $switch;
            $params[':reid']   = $reid;
            pdo_query($sql, $params);
            exit();
        }
        $sql    = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid';
        $status = $_GPC['status'];
        if ($status != '') {
            $sql .= " and status=" . intval($status);
        }
        $ds = pdo_fetchall($sql, array(
            ':weid' => $_W['uniacid']
        ));
        foreach ($ds as &$item) {
            $item['isstart'] = $item['starttime'] > 0;
            $item['switch']  = $item['status'];
            $item['link']    = $_W['siteroot'] . "app/" . $this->createMobileUrl('dayu_yuyuepay', array(
                'id' => $item['reid']
            ));
            $item['mylink']  = $_W['siteroot'] . "app/" . $this->createMobileUrl('mydayu_yuyuepay', array(
                'name' => 'dayu_yuyuepay',
                'm' => 'dayu_yuyuepay',
                'weid' => $item[weid],
                'id' => $item[reid]
            ));
        }
        include $this->template('display');
    }
    public function doWebyyxmpost()
    {
        global $_W, $_GPC;
        $xmid    = intval($_GPC['id']);
        $weid    = $_W['uniacid'];
        $outlets = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE weid = :weid ORDER BY reid DESC", array(
            ':weid' => $weid
        ));
        if (checksubmit()) {
            $xmrecord                 = array();
            $xmrecord['title']        = trim($_GPC['activity']);
            $xmrecord['weid']         = $_W['uniacid'];
            $xmrecord['reid']         = $_GPC['gid'];
            $xmrecord['price']        = $_GPC['price'];
            $xmrecord['isshow']       = $_GPC['isshow'];
            $xmrecord['displayorder'] = $_GPC['displayorder'];
            if (empty($xmid)) {
                pdo_insert('dayu_yuyuepay_xiangmu', $xmrecord);
                $xmid = pdo_insertid();
                if (!$xmid) {
                    message('保存预约项目失败, 请稍后重试.');
                }
            } else {
                if (pdo_update('dayu_yuyuepay_xiangmu', $xmrecord, array(
                    'id' => $xmid
                )) === false) {
                    message('保存预约项目失败, 请稍后重试.');
                }
            }
            message('保存预约项目成功.', 'refresh');
        }
        if ($xmid) {
            $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_xiangmu') . ' WHERE `weid`=:weid AND `id`=:xmid';
            $params          = array();
            $params[':weid'] = $_W['uniacid'];
            $params[':xmid'] = $xmid;
            $xmactivity      = pdo_fetch($sql, $params);
        }
        include $this->template('yyxmpost');
    }
    public function doWebyyxmdisplay()
    {
        global $_W, $_GPC;
        $sql = 'SELECT * FROM ' . tablename('dayu_yuyuepay_xiangmu') . ' WHERE `weid`=:weid';
        $ds  = pdo_fetchall($sql, array(
            ':weid' => $_W['uniacid']
        ));
        foreach ($ds as &$item) {
            $item['isshow'] = $item['isshow'] == '0' ? '不显示' : '显示';
        }
        include $this->template('yyxmdisplay');
    }
    public function doWebXmDelete()
    {
        global $_W, $_GPC;
        $xmid = intval($_GPC['id']);
        if ($xmid > 0) {
            $params          = array();
            $params[':xmid'] = $xmid;
            $sql             = 'DELETE FROM ' . tablename('dayu_yuyuepay_xiangmu') . ' WHERE `id`=:xmid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }
    public function doMobilegetjs()
    {
        global $_GPC, $_W;
        $jss = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE id = :id and weid = :weid AND reid = :reid AND isshow=1 ORDER BY displayorder DESC,id DESC", array(
            ':id' => $xmid,
            ':weid' => $_W['uniacid'],
            ':reid' => $_GPC['id']
        ));
        if (empty($jss)) {
            $result['status'] = 0;
            $result['jss']    = '该门店暂时无法为您提供服务.';
            message($result, '', 'ajax');
        }
        $result['status'] = 1;
        $result['jss']    = $jss;
        message($result, '', 'ajax');
    }
    public function payResult($params)
    {
        $fee             = intval($params['fee']);
        $data            = array(
            'paystatus' => $params['result'] == 'success' ? 2 : 1
        );
        $paytype         = array(
            'credit' => '2',
            'wechat' => '1',
            'alipay' => '1',
            'delivery' => '2'
        );
        $data['paytype'] = $paytype[$params['type']];
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
        }
        if ($params['type'] == 'delivery') {
            $data['paystatus'] = 1;
        }
        pdo_update('dayu_yuyuepay_info', $data, array(
            'ordersn' => $params['tid']
        ));
        if ($params['from'] == 'return') {
            $this->setOrderCredit($params['tid']);
            $setting = uni_setting($_W['uniacid'], array(
                'creditbehaviors'
            ));
            $credit  = $setting['creditbehaviors']['currency'];
            if ($params['type'] == $credit) {
                message('支付成功！', $this->createMobileUrl('mydayu_yuyuepay', array(
                    'name' => 'dayu_yuyuepay',
                    'weid' => $_W['uniacid'],
                    'id' => $order['reid']
                )), 'success');
            } else {
                message('支付成功！', '../../app/' . $this->createMobileUrl('mydayu_yuyuepay', array(
                    'name' => 'dayu_yuyuepay',
                    'weid' => $_W['uniacid'],
                    'id' => $order['reid']
                )), 'success');
            }
        }
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        if (empty($_W['openid'])) {
            checkauth();
        }
        $orderid = intval($_GPC['orderid']);
        $order   = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_info') . " WHERE rerid = :rerid", array(
            ':rerid' => $orderid
        ));
        $xm      = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE weid = :weid and reid = :reid and id = :xmid", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $order['reid'],
            ':xmid' => $order['xmid']
        ));
        if ($order['paystatus'] != '1') {
            message('抱歉，您的预约已经付款或是被关闭，请查看预约列表。', $this->createMobileUrl('mydayu_yuyuepay', array(
                'name' => 'dayu_yuyuepay',
                'weid' => $row['weid'],
                'id' => $row['reid']
            )), 'error');
        }
        if (checksubmit()) {
            if ($order['price'] == '0') {
                $this->payResult(array(
                    'tid' => $order['ordersn'],
                    'from' => 'return',
                    'type' => 'credit2'
                ));
                exit;
            }
        }
        $params['tid']     = $order['ordersn'];
        $params['user']    = $_W['openid'];
        $params['fee']     = $order['price'];
        $params['title']   = $xm['title'];
        $params['ordersn'] = $order['ordersn'];
        $params['virtual'] = 1;
        include $this->template('pay');
    }
    public function get_outlet($reid)
    {
        return pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid ", array(
            ':reid' => $reid
        ));
    }
    public function get_xiangmu($reid, $xmid)
    {
        return pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE reid = :reid and id = :xmid and isshow=1", array(
            ':reid' => $reid,
            ':xmid' => $xmid
        ));
    }
    public function get_yuyuepay($reid)
    {
        return pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid and status=1", array(
            ':reid' => $reid
        ));
    }
    public function doWebDelete()
    {
        global $_W, $_GPC;
        $reid = intval($_GPC['id']);
        if ($reid > 0) {
            $params          = array();
            $params[':reid'] = $reid;
            $sql             = 'DELETE FROM ' . tablename('dayu_yuyuepay') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_yuyuepay_info') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            $sql = 'DELETE FROM ' . tablename('dayu_yuyuepay_data') . ' WHERE `reid`=:reid';
            pdo_query($sql, $params);
            message('操作成功.', referer());
        }
        message('非法访问.');
    }
    public function doWebdayu_yuyuepayDelete()
    {
        global $_W, $_GPC;
        $id = intval($_GPC['id']);
        if (!empty($id)) {
            pdo_delete('dayu_yuyuepay_info', array(
                'rerid' => $id
            ));
            pdo_delete('dayu_yuyuepay_data', array(
                'rerid' => $id
            ));
        }
        message('操作成功.', referer());
    }
    public function doWebPost()
    {
        global $_W, $_GPC;
        $reid    = intval($_GPC['id']);
        $hasData = false;
        if ($reid) {
            $sql = 'SELECT COUNT(*) FROM ' . tablename('dayu_yuyuepay_info') . ' WHERE `reid`=' . $reid;
            if (pdo_fetchcolumn($sql) > 0) {
                $hasData = true;
            }
        }
        if (checksubmit()) {
            $record                = array();
            $record['title']       = trim($_GPC['activity']);
            $record['weid']        = $_W['uniacid'];
            $record['description'] = trim($_GPC['description']);
            $record['content']     = trim($_GPC['content']);
            $record['information'] = trim($_GPC['information']);
            if (!empty($_GPC['thumb'])) {
                $record['thumb'] = $_GPC['thumb'];
                load()->func('file');
                file_delete($_GPC['thumb-old']);
            }
            $record['status']       = intval($_GPC['status']);
            $record['inhome']       = intval($_GPC['inhome']);
            $record['pretotal']     = intval($_GPC['pretotal']);
            $record['pay']          = intval($_GPC['pay']);
            $record['xmshow']       = intval($_GPC['xmshow']);
            $record['xmname']       = trim($_GPC['xmname']);
            $record['yuyuename']    = trim($_GPC['yuyuename']);
            $record['starttime']    = strtotime($_GPC['starttime']);
            $record['endtime']      = strtotime($_GPC['endtime']);
            $record['noticeemail']  = trim($_GPC['noticeemail']);
            $record['k_templateid'] = trim($_GPC['k_templateid']);
            $record['kfid']         = trim($_GPC['kfid']);
            $record['m_templateid'] = trim($_GPC['m_templateid']);
            $record['code']         = intval($_GPC['code']);
            $record['kfirst']       = trim($_GPC['kfirst']);
            $record['kfoot']        = trim($_GPC['kfoot']);
            $record['mfirst']       = trim($_GPC['mfirst']);
            $record['mfoot']        = trim($_GPC['mfoot']);
            $record['mobile']       = trim($_GPC['mobile']);
            $record['accountsid']   = trim($_GPC['accountsid']);
            $record['tokenid']      = trim($_GPC['tokenid']);
            $record['appId']        = trim($_GPC['appId']);
            $record['templateId']   = trim($_GPC['templateId']);
            $record['mname']        = trim($_GPC['mname']);
            $record['skins']        = trim($_GPC['skins']);
            $record['share_url']    = trim($_GPC['share_url']);
            $record['follow']       = intval($_GPC['follow']);
            $record['is_time']      = intval($_GPC['is_time']);
            if (empty($reid)) {
                $record['status']     = 1;
                $record['createtime'] = TIMESTAMP;
                pdo_insert('dayu_yuyuepay', $record);
                $reid = pdo_insertid();
                if (!$reid) {
                    message('保存预约失败, 请稍后重试.');
                }
            } else {
                if (pdo_update('dayu_yuyuepay', $record, array(
                    'reid' => $reid
                )) === false) {
                    message('保存预约失败, 请稍后重试.');
                }
            }
            if (!$hasData) {
                $sql             = 'DELETE FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid';
                $params          = array();
                $params[':reid'] = $reid;
                pdo_query($sql, $params);
                foreach ($_GPC['title'] as $k => $v) {
                    $field                 = array();
                    $field['reid']         = $reid;
                    $field['title']        = trim($v);
                    $field['displayorder'] = range_limit($_GPC['displayorder'][$k], 0, 254);
                    $field['type']         = $_GPC['type'][$k];
                    $field['essential']    = $_GPC['essentialvalue'][$k] == 'true' ? 1 : 0;
                    $field['bind']         = $_GPC['bind'][$k];
                    $field['value']        = $_GPC['value'][$k];
                    $field['value']        = urldecode($field['value']);
                    $field['description']  = $_GPC['desc'][$k];
                    pdo_insert('dayu_yuyuepay_fields', $field);
                }
            }
            message('保存预约成功.', 'refresh');
        }
        $types             = array();
        $types['number']   = '数字(number)';
        $types['text']     = '字串(text)';
        $types['textarea'] = '文本(textarea)';
        $types['radio']    = '单选(radio)';
        $types['checkbox'] = '多选(checkbox)';
        $types['select']   = '选择(select)';
        $types['calendar'] = '日期(calendar)';
        $types['range']    = '时间(range)';
        $types['email']    = '邮件(email)';
        $types['image']    = '上传图片(image)';
        $types['reside']   = '省市区(reside)';
        $fields            = fans_fields();
        if ($reid) {
            $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
            $params          = array();
            $params[':weid'] = $_W['uniacid'];
            $params[':reid'] = $reid;
            $activity        = pdo_fetch($sql, $params);
            $activity['starttime'] && $activity['starttime'] = date('Y-m-d H:i:s', $activity['starttime']);
            $activity['endtime'] && $activity['endtime'] = date('Y-m-d H:i:s', $activity['endtime']);
            if ($activity) {
                $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid ORDER BY `displayorder` DESC';
                $params          = array();
                $params[':reid'] = $reid;
                $ds              = pdo_fetchall($sql, $params);
            }
        }
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $reply           = pdo_fetch($sql, $params);
        if (!$reply) {
            $activity = array(
                "mname" => "我的预约",
                "xmname" => "请选择服务项目",
                "yuyuename" => "预约时间",
                "kfirst" => "有新的客户预约，请及时确认",
                "kfoot" => "点击确认预约，或修改预约时间",
                "mfirst" => "预约结果通知",
                "mfoot" => "如有疑问，请致电联系我们",
                "information" => "您的预约申请我们已经收到, 请等待客服确认.",
                "pretotal" => "100",
                "endtime" => date('Y-m-d H:i:s', strtotime('+30 day'))
            );
        }
        include $this->template('post');
    }
    public function get_skin($sections)
    {
        return pdo_fetchall("SELECT * FROM " . tablename('site_templates') . " WHERE sections = :sections ", array(
            ':sections' => $sections
        ));
    }
    public function get_userinfo($weid, $from_user)
    {
        load()->model('mc');
        return mc_fetch($from_user);
    }
    public function doMobiledayu_yuyuepay()
    {
        global $_W, $_GPC;
        $from_user       = $_W['openid'];
        $userinfo        = $this->get_userinfo($weid, $from_user);
        $reid            = intval($_GPC['id']);
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity        = pdo_fetch($sql, $params);
        $xms             = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE weid = :weid and reid = :reid and isshow=1 ORDER BY displayorder DESC,id DESC", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $reid
        ));
        $xms_class       = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE weid = :weid and reid = :reid and isshow=1 ORDER BY displayorder DESC,id DESC LIMIT 1", array(
            ':weid' => $_W['uniacid'],
            ':reid' => $reid
        ));
        $title           = $activity['title'];
        $yuyuetime       = date('Y-m-d H:i', time() + 3600);
        if ($activity['status'] != '1') {
            message('当前预约已经停止.');
        }
        if (!$activity) {
            message('非法访问.');
        }
        if ($activity['starttime'] > TIMESTAMP) {
            message('当前预约还未开始！');
        }
        if ($activity['endtime'] < TIMESTAMP) {
            message('当前预约已经结束！');
        }
        $follow = pdo_fetchcolumn("select follow from " . tablename('mc_mapping_fans') . " where openid=:openid and uniacid=:uniacid order by `fanid` desc", array(
            ":openid" => $from_user,
            ":uniacid" => $_W['uniacid']
        ));
        if ($follow == 0 && $activity['follow'] == 1) {
            if (!empty($activity['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header('Location: ' . $activity['share_url'] . "");
                exit();
            }
            $isshare = 1;
            $running = false;
            message('请先关注公共号。');
        }
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid` = :reid ORDER BY `displayorder` DESC';
        $params          = array();
        $params[':reid'] = $reid;
        $ds              = pdo_fetchall($sql, $params);
        if (!$ds) {
            message('非法访问.');
        }
        $initRange = $initCalendar = false;
        $binds     = array();
        foreach ($ds as &$r) {
            if ($r['type'] == 'range') {
                $initRange = true;
            }
            if ($r['type'] == 'calendar') {
                $initCalendar = true;
            }
            if ($r['value']) {
                $r['options'] = explode(',', $r['value']);
            }
            if ($r['bind']) {
                $binds[$r['type']] = $r['bind'];
            }
            if ($r['type'] == 'reside') {
                $reside = $r;
            }
        }
        $xm = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_xiangmu') . " WHERE id = :id", array(
            ':id' => $_GPC['xmid']
        ));
        if (checksubmit('submit')) {
            $pretotal = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE reid = :reid AND openid = :openid", array(
                ':reid' => $reid,
                ':openid' => $_W['openid']
            ));
            if ($pretotal >= $activity['pretotal']) {
                message('抱歉,每人只能预约' . $activity['pretotal'] . "次！", referer(), 'error');
            }
            $row               = array();
            $row['reid']       = $reid;
            $row['member']     = $_GPC['member'];
            $row['mobile']     = $_GPC['mobile'];
            $row['openid']     = $_W['openid'];
            $row['xmid']       = $_GPC['xmid'];
            $row['ordersn']    = date('md') . random(5, 1);
            $row['price']      = $xm['price'];
            $row['paystatus']  = 1;
            $row['paytype']    = intval($_GPC['paytype']);
            $row['yuyuetime']  = strtotime($_GPC['yuyuetime']);
            $row['createtime'] = TIMESTAMP;
            $datas             = $fields = $update = array();
            foreach ($ds as $value) {
                $fields[$value['refid']] = $value;
            }
            foreach ($_GPC as $key => $value) {
                if (strexists($key, 'field_')) {
                    $bindFiled = substr(strrchr($key, '_'), 1);
                    if (!empty($bindFiled)) {
                        $update[$bindFiled] = $value;
                    }
                    $refid = intval(str_replace('field_', '', $key));
                    $field = $fields[$refid];
                    if ($refid && $field) {
                        $entry          = array();
                        $entry['reid']  = $reid;
                        $entry['rerid'] = 0;
                        $entry['refid'] = $refid;
                        if (in_array($field['type'], array(
                            'number',
                            'text',
                            'calendar',
                            'email',
                            'textarea',
                            'radio',
                            'range',
                            'select',
                            'image'
                        ))) {
                            $entry['data'] = strval($value);
                        }
                        if (in_array($field['type'], array(
                            'checkbox'
                        ))) {
                            if (!is_array($value))
                                continue;
                            $entry['data'] = implode(';', $value);
                        }
                        $datas[] = $entry;
                    }
                }
            }
            if ($_FILES) {
                load()->func('file');
                foreach ($_FILES as $key => $file) {
                    if (strexists($key, 'field_')) {
                        $refid = intval(str_replace('field_', '', $key));
                        $field = $fields[$refid];
                        if ($refid && $field && $file['name'] && $field['type'] == 'image') {
                            $entry          = array();
                            $entry['reid']  = $reid;
                            $entry['rerid'] = 0;
                            $entry['refid'] = $refid;
                            $ret            = file_upload($file);
                            if (!$ret['success']) {
                                message('上传图片失败, 请稍后重试.');
                            }
                            $entry['data'] = trim($ret['path']);
                            $datas[]       = $entry;
                        }
                    }
                }
            }
            if (!empty($_GPC['reside'])) {
                if (in_array('reside', $binds)) {
                    $update['resideprovince'] = $_GPC['reside']['province'];
                    $update['residecity']     = $_GPC['reside']['city'];
                    $update['residedist']     = $_GPC['reside']['district'];
                }
                foreach ($_GPC['reside'] as $key => $value) {
                    $resideData          = array(
                        'reid' => $reside['reid']
                    );
                    $resideData['rerid'] = 0;
                    $resideData['refid'] = $reside['refid'];
                    $resideData['data']  = $value;
                    $datas[]             = $resideData;
                }
            }
            $update['realname'] = $_GPC['member'];
            $update['mobile']   = $_GPC['mobile'];
            if (!empty($update)) {
                load()->model('mc');
                mc_update($_W['member']['uid'], $update);
            }
            if (empty($datas)) {
                message('非法访问.', '', 'error');
            }
            if (pdo_insert('dayu_yuyuepay_info', $row) != 1) {
                message('保存失败.');
            }
            $rerid   = pdo_insertid();
            $orderid = pdo_insertid();
            if (empty($rerid)) {
                message('保存失败.');
            }
            foreach ($datas as &$r) {
                $r['rerid'] = $rerid;
                pdo_insert('dayu_yuyuepay_data', $r);
            }
            if (empty($activity['starttime'])) {
                $record              = array();
                $record['starttime'] = TIMESTAMP;
                pdo_update('dayu_yuyuepay', $record, array(
                    'reid' => $reid
                ));
            }
            foreach ($datas as $row) {
                $img = "<img src='{$_W['attachurl']}";
                if (substr($row['data'], 0, 6) == 'images') {
                    $body = $fields[$row['refid']]['title'] . ':' . $img . $row['data'] . " ' width='90';height='120'/>";
                }
                $body .= '<h4>' . $fields[$row['refid']]['title'] . ':' . $row['data'] . '</h4>';
                $bodym .= $fields[$row['refid']]['title'] . ':' . $row['data'] . ',';
            }
            if (!empty($datas) && !empty($activity['noticeemail'])) {
                $yxiangmu = $this->get_xiangmu($row['reid'], $_GPC['xmid']);
                load()->func('communication');
                ihttp_email($activity['noticeemail'], $activity['title'] . '的预约提醒', '<h4>姓名：' . $_GPC['member'] . '</h4><h4>手机：' . $_GPC['mobile'] . '</h4><h4>预约时间：' . $_GPC['yuyuetime'] . '</h4><h4>预约项目：' . $yxiangmu['title'] . '</h4>' . $body);
            }
            if (!empty($activity['mobile'])) {
                $yxiangmu = $this->get_xiangmu($row['reid'], $_GPC['xmid']);
                $this->SendSms($activity['mobile'], $row['member'], $row['mobile'], $yxiangmu['title'], $activity['accountsid'], $activity['tokenid'], $activity['appId'], $activity['templateId']);
            }
            if (!empty($datas) && !empty($activity['kfid']) && !empty($activity['k_templateid'])) {
                $ymember  = $_GPC['member'];
                $ymobile  = $_GPC['mobile'];
                $yxiangmu = $this->get_xiangmu($row['reid'], $_GPC['xmid']);
                $ytime    = date('Y-m-d H:i:s', $row['yuyuetime']);
                if (!empty($activity['kfirst'])) {
                    $kfirst = $activity['kfirst'];
                } else {
                    $kfirst = "有新的客户预约，请及时确认";
                }
                if (!empty($activity['kfoot'])) {
                    $kfoot = $activity['kfoot'];
                } else {
                    $kfoot = "点击确认预约，或修改预约时间。";
                }
                $template = array(
                    "touser" => $activity['kfid'],
                    "template_id" => $activity['k_templateid'],
                    "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageyuyues', array(
                        'name' => 'dayu_yuyuepay',
                        'weid' => $row['weid'],
                        'id' => $row['reid']
                    )),
                    "topcolor" => "#FF0000",
                    "data" => array(
                        'first' => array(
                            'value' => urlencode($kfirst),
                            'color' => "#743A3A"
                        ),
                        'keyword1' => array(
                            'value' => urlencode($ymember),
                            'color' => '#000000'
                        ),
                        'keyword2' => array(
                            'value' => urlencode($ymobile),
                            'color' => '#000000'
                        ),
                        'keyword3' => array(
                            'value' => urlencode($_GPC['yuyuetime']),
                            'color' => '#000000'
                        ),
                        'keyword4' => array(
                            'value' => urlencode($yxiangmu['title'] . " - " . $yxiangmu['price'] . "元"),
                            'color' => "#FF0000"
                        ),
                        'remark' => array(
                            'value' => urlencode($kfoot),
                            'color' => "#008000"
                        )
                    )
                );
                load()->func('communication');
                $this->send_template_message(urldecode(json_encode($template)));
            }
            if ($_GPC['paytype'] == 1) {
                message('预约提交成功，现在跳转至付款页面...', $this->createMobileUrl('pay', array(
                    'orderid' => $orderid,
                    'weid' => $_GPC['weid'],
                    'id' => $_GPC['id']
                )), 'success');
            } else if ($_GPC['paytype'] == 2 || $activity['pay'] == 1) {
                message($activity['information'], $this->createMobileUrl('mydayu_yuyuepay', array(
                    'name' => 'dayu_yuyuepay',
                    'weid' => $row['weid'],
                    'id' => $row['reid']
                )));
            }
        }
        foreach ($binds as $key => $value) {
            if ($value == 'reside') {
                unset($binds[$key]);
                $binds[] = 'resideprovince';
                $binds[] = 'residecity';
                $binds[] = 'residedist';
                break;
            }
        }
        if (!empty($_W['openid']) && !empty($binds)) {
            $profile = fans_search($_W['openid'], $binds);
            if ($profile['gender']) {
                if ($profile['gender'] == '0')
                    $profile['gender'] = '保密';
                if ($profile['gender'] == '1')
                    $profile['gender'] = '男';
                if ($profile['gender'] == '2')
                    $profile['gender'] = '女';
            }
            foreach ($ds as &$r) {
                if ($profile[$r['bind']]) {
                    $r['default'] = $profile[$r['bind']];
                }
            }
        }
        load()->func('tpl');
        $_share['title']   = $activity['title'];
        $_share['content'] = $activity['description'];
        $_share['imgUrl']  = tomedia($activity['thumb']);
        include $this->template($activity['skins']);
    }
    public function doMobileMydayu_yuyuepay()
    {
        global $_W, $_GPC;
        $operation       = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $reid            = intval($_GPC['id']);
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity        = pdo_fetch($sql, $params);
        $status          = intval($_GPC['status']);
        if ($status == 2) {
            $where .= " and ( status=2 or status=-1 )";
        } else {
            $where .= " and status=$status";
        }
        $xm = $this->get_xiangmu($reid);
        if ($operation == 'display') {
            $pindex = max(1, intval($_GPC['page']));
            $psize  = 10;
            if ($reid) {
                $sql               = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid and reid = :reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
                $params            = array();
                $params[':reid']   = $reid;
                $params[':openid'] = $_W['openid'];
            } else {
                $sql               = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
                $params            = array();
                $params[':openid'] = $_W['openid'];
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid $where ", $params);
            $pager = pagination($total, $pindex, $psize);
            $rows  = pdo_fetchall($sql, $params);
            $list  = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE weid = '{$_W['uniacid']}' and status='1' ORDER BY reid DESC", array(), 'reid');
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $reids[$row['reid']] = $row['reid'];
                }
            }
        } elseif ($operation == 'detail') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid AND rerid = :rerid", array(
                ':openid' => $_W['openid'],
                ':rerid' => $id
            ));
            if (empty($row)) {
                message('我的预约不存在或是已经被删除！');
            }
            $dayu_yuyuepay            = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid", array(
                ':reid' => $row['reid']
            ));
            $dayu_yuyuepay['content'] = htmlspecialchars_decode($dayu_yuyuepay['content']);
            $sql                      = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
            $params                   = array();
            $params[':reid']          = $row['reid'];
            $fields                   = pdo_fetchall($sql, $params);
            if (empty($fields)) {
                message('非法访问.');
            }
            $ds = $fids = array();
            foreach ($fields as $f) {
                $ds[$f['refid']]['fid']   = $f['title'];
                $ds[$f['refid']]['type']  = $f['type'];
                $ds[$f['refid']]['refid'] = $f['refid'];
                $fids[]                   = $f['refid'];
            }
            $fids          = implode(',', $fids);
            $row['fields'] = array();
            $sql           = 'SELECT * FROM ' . tablename('dayu_yuyuepay_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
            $fdatas        = pdo_fetchall($sql, $params);
            foreach ($fdatas as $fd) {
                $row['fields'][$fd['refid']] = $fd['data'];
            }
            foreach ($ds as $value) {
                if ($value['type'] == 'reside') {
                    $row['fields'][$value['refid']] = '';
                    foreach ($fdatas as $fdata) {
                        if ($fdata['refid'] == $value['refid']) {
                            $row['fields'][$value['refid']] .= $fdata['data'];
                        }
                    }
                    break;
                }
            }
        }
        $rerid            = intval($_GPC['id']);
        $record           = array();
        $record['status'] = intval($_GPC['status']);
        $ymember          = $row['member'];
        $ymobile          = $row['mobile'];
        $ytime            = date('Y-m-d H:i:s', TIMESTAMP);
        $template         = array(
            "touser" => $dayu_yuyuepay['kfid'],
            "template_id" => $dayu_yuyuepay['k_templateid'],
            "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageyuyues', array(
                'name' => 'dayu_yuyuepay',
                'weid' => $row['weid'],
                'id' => $row['reid'],
                'status' => '9'
            )),
            "topcolor" => "#FF0000",
            "data" => array(
                'first' => array(
                    'value' => urlencode("客户已取消了预约"),
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => urlencode($ymember),
                    'color' => '#000000'
                ),
                'keyword2' => array(
                    'value' => urlencode($ymobile),
                    'color' => '#000000'
                ),
                'keyword3' => array(
                    'value' => urlencode($ytime),
                    'color' => '#000000'
                ),
                'keyword4' => array(
                    'value' => urlencode("客户已取消了预约"),
                    'color' => "#FF0000"
                ),
                'remark' => array(
                    'value' => urlencode("\\n客户取消预约了。"),
                    'color' => "#008000"
                )
            )
        );
        if ($_W['ispost']) {
            load()->func('communication');
            $this->send_template_message(urldecode(json_encode($template)));
            pdo_update('dayu_yuyuepay_info', $record, array(
                'rerid' => $rerid
            ));
            message('取消预约成功', referer(), 'success');
        }
        include $this->template('dayu_yuyuepay');
    }
    public function doMobileMydayu_yuyuepaylist()
    {
        global $_W, $_GPC;
        $operation       = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $reid            = intval($_GPC['id']);
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity        = pdo_fetch($sql, $params);
        $status          = intval($_GPC['status']);
        if ($status == 2) {
            $where .= " and ( status=2 or status=-1 )";
        } else {
            $where .= " and status=$status";
        }
        $xm     = $this->get_xiangmu($reid);
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;
        if ($operation == 'display') {
            $sql               = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid and reid = :reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $params            = array();
            $params[':reid']   = $reid;
            $params[':openid'] = $_W['openid'];
            $total             = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE openid = :openid $where ", $params);
            $pager             = pagination($total, $pindex, $psize);
            $rows              = pdo_fetchall($sql, $params);
            $list              = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE weid = '{$_W['uniacid']}' ORDER BY reid DESC", array(), 'reid');
        } elseif ($operation == 'managelist' && $_W['openid'] == $activity['kfid'] && !empty($reid)) {
            $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE `reid`=:reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $params          = array();
            $params[':reid'] = $reid;
            $total           = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE reid = :reid $where ", $params);
            $pager           = pagination($total, $pindex, $psize);
            $rows            = pdo_fetchall($sql, $params);
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $reids[$row['reid']] = $row['reid'];
                }
                $dayu_yuyuepay = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid", array(
                    ':reid' => $row['reid']
                ));
            }
        }
        include $this->template('dayu_yuyuepaylist');
    }
    public function doMobilemanageyuyues()
    {
        global $_W, $_GPC;
        if (empty($_W['openid'])) {
            message("请在微信中访问");
        }
        load()->func('tpl');
        $operation       = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
        $reid            = intval($_GPC['id']);
        $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay') . ' WHERE `weid`=:weid AND `reid`=:reid';
        $params          = array();
        $params[':weid'] = $_W['uniacid'];
        $params[':reid'] = $reid;
        $activity        = pdo_fetch($sql, $params);
        $status          = intval($_GPC['status']);
        if ($status == 2) {
            $where .= " and ( status=2 or status=-1 )";
        } else {
            $where .= " and status=$status";
        }
        if ($operation == 'display' && $_W['openid'] == $activity['kfid'] && !empty($reid)) {
            $pindex          = max(1, intval($_GPC['page']));
            $psize           = 10;
            $sql             = 'SELECT * FROM ' . tablename('dayu_yuyuepay_info') . " WHERE `reid`=:reid $where ORDER BY createtime DESC,rerid DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
            $params          = array();
            $params[':reid'] = $reid;
            $total           = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('dayu_yuyuepay_info') . " WHERE reid = :reid $where ", $params);
            $pager           = pagination($total, $pindex, $psize);
            $rows            = pdo_fetchall($sql, $params);
            $list            = pdo_fetchall("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE weid = '{$_W['uniacid']}' ORDER BY reid DESC", array(), 'reid');
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $reids[$row['reid']] = $row['reid'];
                }
                $dayu_yuyuepay = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid", array(
                    ':reid' => $row['reid']
                ));
            }
        } elseif ($operation == 'detail') {
            $id  = intval($_GPC['id']);
            $row = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay_info') . " WHERE rerid = :rerid", array(
                ':rerid' => $id
            ));
            if (empty($row)) {
                message('预约不存在或是已经被删除！');
            }
            $dayu_yuyuepay            = pdo_fetch("SELECT * FROM " . tablename('dayu_yuyuepay') . " WHERE reid = :reid", array(
                ':reid' => $row['reid']
            ));
            $dayu_yuyuepay['content'] = htmlspecialchars_decode($dayu_yuyuepay['content']);
            $sql                      = 'SELECT * FROM ' . tablename('dayu_yuyuepay_fields') . ' WHERE `reid`=:reid ORDER BY `refid`';
            $params                   = array();
            $params[':reid']          = $row['reid'];
            $fields                   = pdo_fetchall($sql, $params);
            if (empty($fields)) {
                message('非法访问.');
            }
            $ds = $fids = array();
            foreach ($fields as $f) {
                $ds[$f['refid']]['fid']   = $f['title'];
                $ds[$f['refid']]['type']  = $f['type'];
                $ds[$f['refid']]['refid'] = $f['refid'];
                $fids[]                   = $f['refid'];
            }
            $fids          = implode(',', $fids);
            $row['fields'] = array();
            $sql           = 'SELECT * FROM ' . tablename('dayu_yuyuepay_data') . " WHERE `reid`=:reid AND `rerid`='{$row['rerid']}' AND `refid` IN ({$fids})";
            $fdatas        = pdo_fetchall($sql, $params);
            foreach ($fdatas as $fd) {
                $row['fields'][$fd['refid']] = $fd['data'];
            }
            foreach ($ds as $value) {
                if ($value['type'] == 'reside') {
                    $row['fields'][$value['refid']] = '';
                    foreach ($fdatas as $fdata) {
                        if ($fdata['refid'] == $value['refid']) {
                            $row['fields'][$value['refid']] .= $fdata['data'];
                        }
                    }
                    break;
                }
            }
        }
        $yuyuetime        = date('Y-m-d H:i', $row['yuyuetime']);
        $rerid            = intval($_GPC['id']);
        $record           = array();
        $record['status'] = intval($_GPC['status']);
        if (!empty($_GPC['paystatus'])) {
            $record['paystatus'] = intval($_GPC['paystatus']);
        }
        $record['yuyuetime'] = strtotime($_GPC['yuyuetime']);
        $record['kfinfo']    = $_GPC['kfinfo'];
        if ($_GPC['status'] == '0') {
            $huifu = '等待客服确认：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '1') {
            $huifu = '客服已确认：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '2') {
            $huifu = '客服已拒绝：' . $_GPC['kfinfo'];
        } elseif ($_GPC['status'] == '3') {
            $huifu = '服务完成。';
        }
        $hexiao   = "dayu_yuyuepay_shareQrcode" . $_W['uniacid'];
        $ymember  = $row['member'];
        $ymobile  = $row['mobile'];
        $yxiangmu = $this->get_xiangmu($row['reid'], $row['xmid']);
        $ytime    = date('Y-m-d H:i:s', $row['yuyuetime']);
        if (!empty($dayu_yuyuepay['mfirst'])) {
            $mfirst = $dayu_yuyuepay['mfirst'];
        } else {
            $mfirst = "预约结果通知";
        }
        if (!empty($dayu_yuyuepay['mfoot'])) {
            $mfoot = $dayu_yuyuepay['mfoot'];
        } else {
            $mfoot = "如有疑问，请致电联系我们。";
        }
        $template = array(
            "touser" => $row['openid'],
            "template_id" => $dayu_yuyuepay['m_templateid'],
            "url" => $_W['siteroot'] . 'app/' . $this->createMobileUrl('mydayu_yuyuepay', array(
                'name' => 'dayu_yuyuepay',
                'weid' => $row['weid'],
                'id' => $row['reid']
            )),
            "topcolor" => "#FF0000",
            "data" => array(
                'first' => array(
                    'value' => urlencode($mfirst),
                    'color' => "#743A3A"
                ),
                'keyword1' => array(
                    'value' => urlencode($ymember),
                    'color' => '#000000'
                ),
                'keyword2' => array(
                    'value' => urlencode($yxiangmu['title'] . " - " . $yxiangmu['price'] . "元"),
                    'color' => '#000000'
                ),
                'keyword3' => array(
                    'value' => urlencode($_GPC['yuyuetime']),
                    'color' => '#000000'
                ),
                'keyword4' => array(
                    'value' => urlencode($huifu),
                    'color' => "#FF0000"
                ),
                'remark' => array(
                    'value' => urlencode($mfoot),
                    'color' => "#008000"
                )
            )
        );
        if ($_W['ispost']) {
            include "plugin/phpqrcode.php";
            $value                = $_W['siteroot'] . 'app/' . $this->createMobileUrl('manageyuyues', array(
                'name' => 'dayu_yuyuepay',
                'op' => 'detail',
                'id' => $rerid
            ));
            $errorCorrectionLevel = "L";
            $matrixPointSize      = "4";
            $imgname              = "hexiao$rerid.png";
            $imgurl               = "../addons/dayu_yuyuepay/hexiao/$imgname";
            QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize);
            load()->func('communication');
            $this->send_template_message(urldecode(json_encode($template)));
            pdo_update('dayu_yuyuepay_info', $record, array(
                'rerid' => $rerid
            ));
            message('修改成功', $this->createMobileUrl('manageyuyues', array(
                'name' => 'dayu_yuyuepay',
                'weid' => $row['weid'],
                'id' => $row['reid']
            )), 'success');
        }
        include $this->template('manage_yuyuepay');
    }
    public function isHy($weid, $from_user)
    {
        load()->model('mc');
        $fans = mc_fetch($from_user);
        if (!empty($fans)) {
            $card = pdo_fetch("SELECT * FROM " . tablename("mc_card_members") . " WHERE uniacid=:uniacid AND uid=:uid ", array(
                ':uniacid' => $weid,
                ':uid' => $fans['uid']
            ));
        }
        if (empty($card)) {
            return false;
        } else {
            return true;
        }
    }
    public function send_template_message($data)
    {
        global $_W, $_GPC;
        $atype        = 'weixin';
        $account_code = "account_weixin_code";
        load()->classs('weixin.account');
        $access_token = WeAccount::token();
        $url          = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
        $response     = ihttp_request($url, $data);
        if (is_error($response)) {
            return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
        }
        $result = @json_decode($response['content'], true);
        if (empty($result)) {
            return error(-1, "接口调用失败, 元数据: {$response['meta']}");
        } elseif (!empty($result['errcode'])) {
            return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
        }
        return true;
    }
    public function SendSms($telephone, $member, $mobile, $yxiangmu, $accountsid, $tokenid, $appId, $templateId)
    {
        $result['state']       = 0;
        $options['accountsid'] = $accountsid;
        $options['token']      = $tokenid;
        $ucpass                = new Ucpaas($options);
        $appId                 = $appId;
        $to                    = $telephone;
        $templateId            = $templateId;
        $yxiangmu              = $yxiangmu;
        $member                = $member;
        $$mobile               = $$mobile;
        $param                 = "{$member},{$mobile},{$yxiangmu}";
        $iscg                  = $ucpass->templateSMS($appId, $to, $templateId, $param);
    }
}
function tpl_form_field_dateyy($name, $value = array(), $ishour = false)
{
    $s = '';
    if (!defined('INCLUDE_DATE')) {
        $s = '
		<link type="text/css" rel="stylesheet" href="/addons/dayu_yuyuepay/template/mobile/time/datetimepicker.css" />
		<script type="text/javascript" src="/addons/dayu_yuyuepay/template/mobile/time/datetimepicker.js"></script>';
    }
    define('INCLUDE_DATE', true);
    if (strexists($name, '[')) {
        $id = str_replace(array(
            '[',
            ']'
        ), '_', $name);
    } else {
        $id = $name;
    }
    $value  = empty($value) ? date('Y-m-d', mktime(0, 0, 0)) : $value;
    $ishour = empty($ishour) ? 2 : 0;
    $s .= '
	<input type="text" id="datepicker_' . $id . '" name="' . $name . '" value="' . $value . '" class="datetimepickers datetimepicker" readonly="readonly" />
	<script type="text/javascript">
		$("#datepicker_' . $id . '").datetimepicker({
			format: "yyyy-mm-dd hh:ii",
			minView: "' . $ishour . '",
			//pickerPosition: "top-right",
			autoclose: true
		});
	</script>';
    return $s;
}
function dayu_fans_form($field, $value = '')
{
    switch ($field) {
        case 'reside':
        case 'resideprovince':
        case 'residecity':
        case 'residedist':
            $html = dayu_form_field_district('reside', $value);
            break;
    }
    return $html;
}
function dayu_form_field_district($name, $values = array())
{
    $html = '';
    if (!defined('TPL_INIT_DISTRICT')) {
        $html .= '
		<script type="text/javascript">
			require(["jquery", "district"], function($, dis){
				$(".tpl-district-container").each(function(){
					var elms = {};
					elms.province = $(this).find(".tpl-province")[0];
					elms.city = $(this).find(".tpl-city")[0];
					elms.district = $(this).find(".tpl-district")[0];
					var vals = {};
					vals.province = $(elms.province).attr("data-value");
					vals.city = $(elms.city).attr("data-value");
					vals.district = $(elms.district).attr("data-value");
					dis.render(elms, vals, {withTitle: true});
				});
			});
		</script>';
        define('TPL_INIT_DISTRICT', true);
    }
    if (empty($values) || !is_array($values)) {
        $values = array(
            'province' => '',
            'city' => '',
            'district' => ''
        );
    }
    if (empty($values['province'])) {
        $values['province'] = '';
    }
    if (empty($values['city'])) {
        $values['city'] = '';
    }
    if (empty($values['district'])) {
        $values['district'] = '';
    }
    $html .= '
		<div class="tpl-district-container">
			<div class="col-lg-4">
				<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="tpl-province">
				</select><i></i>
			</div>
			<div class="col-lg-4">
				<select name="' . $name . '[city]" data-value="' . $values['city'] . '" class="tpl-city">
				</select><i></i>
			</div>
			<div class="col-lg-4">
				<select name="' . $name . '[district]" data-value="' . $values['district'] . '" class="tpl-district">
				</select><i></i>
			</div>
		</div>';
    return $html;
}
function dayu_fans_form_class($field, $value = '')
{
    switch ($field) {
        case 'reside':
        case 'resideprovince':
        case 'residecity':
        case 'residedist':
            $html = dayu_form_field_district_class('reside', $value);
            break;
    }
    return $html;
}
function dayu_form_field_district_class($name, $values = array())
{
    $html = '';
    if (!defined('TPL_INIT_DISTRICT')) {
        $html .= '
		<script type="text/javascript">
			require(["jquery", "district"], function($, dis){
				$(".tpl-district-container").each(function(){
					var elms = {};
					elms.province = $(this).find(".tpl-province")[0];
					elms.city = $(this).find(".tpl-city")[0];
					elms.district = $(this).find(".tpl-district")[0];
					var vals = {};
					vals.province = $(elms.province).attr("data-value");
					vals.city = $(elms.city).attr("data-value");
					vals.district = $(elms.district).attr("data-value");
					dis.render(elms, vals, {withTitle: true});
				});
			});
		</script>';
        define('TPL_INIT_DISTRICT', true);
    }
    if (empty($values) || !is_array($values)) {
        $values = array(
            'province' => '',
            'city' => '',
            'district' => ''
        );
    }
    if (empty($values['province'])) {
        $values['province'] = '';
    }
    if (empty($values['city'])) {
        $values['city'] = '';
    }
    if (empty($values['district'])) {
        $values['district'] = '';
    }
    $html .= '
			<div class="form-group">
				<select name="' . $name . '[province]" data-value="' . $values['province'] . '" class="tpl-province">
				</select><i></i>
			</div>
			<div class="form-group">
				<select id="service_cities" name="' . $name . '[city]" data-value="' . $values['city'] . '" class="tpl-city city">
				</select><i></i>
			</div>
			<div class="form-group">
				<select id="service_districts" name="' . $name . '[district]" data-value="' . $values['district'] . '" class="tpl-district district">
				</select><i></i>
			</div>';
    return $html;
}
function tpl_form_image($name, $value)
{
    $thumb = empty($value) ? 'images/global/nopic.jpg' : $value;
    $thumb = tomedia($thumb);
    $html  = <<<EOF

<div class="input-group">
	<input type="text" name="$name" value="$value" class="form-control" autocomplete="off" readonly="readonly">
	<span class="input-group-btn">
		<button class="btn btn-default" onclick="appupload(this)" type="button">上传图片</button>
	</span>
</div>
<span class="help-block">
	<img style="max-height:100px;" src="$thumb" >
</span>

<script>
window.appupload = window.appupload || function(obj){
	require(['jquery', 'util'], function($, u){
		u.image(obj, function(src){
			$(obj).parent().prev().val(src);
			$(obj).parent().parent().next().find('img').attr('src',u.tomedia(src));
		});
	});
}
</script>

EOF;
    return $html;
}