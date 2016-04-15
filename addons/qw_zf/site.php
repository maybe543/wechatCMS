<?php
defined("IN_IA") or die("Access Denied");
class qw_zfModuleSite extends WeModuleSite
{
    public $settings;
    public function __construct()
    {
        global $_W;
        $sql            = 'SELECT `settings` FROM ' . tablename('uni_account_modules') . ' WHERE `uniacid` = :uniacid AND `module` = :module';
        $settings       = pdo_fetchcolumn($sql, array(
            ':uniacid' => $_W['uniacid'],
            ':module' => 'qw_zf'
        ));
        $this->settings = iunserializer($settings);
    }
    public function sendtpl($openid, $url, $template_id, $content)
    {
        global $_GPC, $_W;
        load()->classs("weixin.account");
        load()->func("communication");
        $obj          = new WeiXinAccount();
        $access_token = $obj->fetch_available_token();
        $data         = array(
            'touser' => $openid,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => "#FF0000",
            'data' => $content
        );
        $json         = json_encode($data);
        $url          = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        $ret          = ihttp_post($url, $json);
    }
    public function sendtext($text, $openid)
    {
        global $_GPC, $_W;
        load()->func("tpl");
        load()->model("mc");
        $acc  = WeAccount::create($_W['account']['acid']);
        $send = array(
            "touser" => $openid,
            "msgtype" => "text",
            "text" => array(
                "content" => urlencode($text)
            )
        );
        $res  = $acc->sendCustomNotice($send);
    }
    public function doMobilefu()
    {
        global $_GPC, $_W;
        $set     = $this->settings;
        $openid  = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $follow  = $_W['fans']['follow'];
        if ($_W['isajax']) {
            $fee     = $_GPC['fee'];
            $year    = date('Y', time());
            $danhao  = $year . time() . rand(1000, 2000);
            $data    = array(
                'fee' => $fee,
                'uniacid' => $uniacid,
                'ordersn' => $danhao,
                'openid' => $openid,
                'nickname' => $_W['fans']['tag']['nickname'],
                'status' => 0,
                'title' => $set['title'],
                'xq' => '扫码支付',
                'addtime' => date('Y-m-d H:i:s', time())
            );
            $rs      = pdo_insert('qw_zf_order', $data);
            $orderxq = pdo_fetch("SELECT * FROM" . tablename('qw_zf_order') . "WHERE uniacid = '{$_W['uniacid']}' and ordersn='$danhao'");
            $newid   = $orderxq['id'];
            $params  = array(
                'tid' => $newid,
                'ordersn' => $danhao,
                'title' => $set['title'],
                'user' => $openid,
                'fee' => $fee,
                'module' => 'qw_zf',
                'virtual' => 1
            );
            $params  = base64_encode(json_encode($params));
            if ($rs) {
                print(json_encode(array(
                    'error' => 1,
                    'message' => "已经成功进入微信支付",
                    'arr' => $data,
                    'params' => $params
                )));
            } else {
                print(json_encode(array(
                    "error" => 0,
                    "message" => "米兔源码提醒:请重试"
                )));
            }
        }
        include $this->template('fu');
    }
    public function doMobilesuccess()
    {
        global $_GPC, $_W;
        $id = $_GPC['id'];
        if ($id == '') {
            message('无法访问', $this->createMobileurl('fu'), 'error');
        }
        $uniacid = $_W['uniacid'];
        $xq      = pdo_fetch("SELECT * FROM" . tablename('qw_zf_order') . "WHERE uniacid = '$uniacid' and id='$id'");
        $set     = $this->settings;
        include $this->template('success');
    }
    public function Payresult($params)
    {
        global $_W, $_GPC;
        $openid = $_W['openid'];
        $tranid = $params['tag']['transaction_id'];
        $jx     = pdo_fetch("SELECT * FROM" . tablename('qw_zf_order') . "WHERE transid = '$tranid'");
        if ($jx) {
            message('不得重复提交', $this->createMobileurl('fu'), 'error');
        }
        $set             = $this->settings;
        $fee             = floatval($params['fee']);
        $data            = array(
            'status' => $params['result'] == 'success' ? 1 : 0
        );
        $paytype         = array(
            'credit' => '1',
            'wechat' => '2',
            'alipay' => '2',
            'delivery' => '3'
        );
        $data['paytype'] = $paytype[$params['type']];
        if ($params['type'] == 'wechat') {
            $data['transid'] = $params['tag']['transaction_id'];
            $data['status']  = 1;
        }
        $data['addtime'] = date('Y-m-d H:i:s', time());
        if ($params['from'] == 'return') {
            $url             = '';
            $succ_templateid = $set['succ_templateid'];
            $createtime      = date('Y-m-d H:i:s', $_W['timestamp']);
            $content         = array(
                'first' => array(
                    'value' => '您好，您在' . $createtime . '成功支付'
                ),
                'orderMoneySum' => array(
                    'value' => round($fee, 2) . '元'
                ),
                'orderProductName' => array(
                    'value' => $set['title'] . '的货款'
                ),
                'Remark' => array(
                    'value' => '感谢您的支持!如有疑问请联系收款员。'
                )
            );
            $this->sendtpl($openid, $url, $succ_templateid, $content);
            pdo_update("qw_zf_order", $data, array(
                'id' => $params['tid']
            ));
            load()->model("mc");
            $set['jfbl'] = intval($set['jfbl']) ? intval($set['jfbl']) : 1;
            $addfen      = $fee * $set['jfbl'];
            $uid         = mc_openid2uid($_W['openid']);
            mc_credit_update($uid, 'credit1', $addfen, array(
                $uid,
                '扫码付付付增加积分:' . $addfen
            ));
            $url              = '';
            $addjf_templateid = $set['addjf_templateid'];
            $createtime       = date('Y-m-d H:i:s', $_W['timestamp']);
            $content          = array(
                'first' => array(
                    'value' => '您好，积分已赠出'
                ),
                'keyword1' => array(
                    'value' => $_W['fans']['tag']['nickname']
                ),
                'keyword2' => array(
                    'value' => $addfen
                ),
                'keyword3' => array(
                    'value' => '暂无'
                ),
                'Remark' => array(
                    'value' => '感谢您的支持，积分详情请到会员中心查看。'
                )
            );
            $this->sendtpl($openid, $url, $addjf_templateid, $content);
            $text = "您已经成功支付，积分增加" . $addfen;
            $this->sendtext($text, $openid);
            $huiyuan       = mc_fetch($uid, array(
                'mobile'
            ));
            $glurl         = '';
            $gl_templateid = $set['gl_templateid'];
            $createtime    = date('Y-m-d H:i:s', $_W['timestamp']);
            $content       = array(
                'first' => array(
                    'value' => '详细信息如下'
                ),
                'keyword1' => array(
                    'value' => $params['ordersn']
                ),
                'keyword2' => array(
                    'value' => '￥' . round($fee, 2)
                ),
                'keyword3' => array(
                    'value' => $createtime
                ),
                'keyword4' => array(
                    'value' => $_W['fans']['tag']['nickname']
                ),
                'keyword5' => array(
                    'value' => $huiyuan['mobile']
                ),
                'remark' => array(
                    'value' => '以上是顾客扫码微信支付凭证，请核对！'
                )
            );
            $this->sendtpl($set['gl_openid'], $url, $gl_templateid, $content);
            $iurl = '../../app/' . $this->createMobileUrl('success', array(
                'id' => $params['tid']
            ));
            echo "<meta charset='utf-8'><script type='text/javascript'>
		 window.location.href='$iurl';
	</script>";
        }
    }
    public function doWeborder()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];
        $con     = "";
        $ordersn = trim($_GPC['ordersn']);
        $status  = $_GPC['status'];
        if ($ordersn != '') {
            $con .= " and ordersn LIKE '%$ordersn%'";
        }
        if ($status != '') {
            $con .= " and status = '$status'";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize  = 15;
        $list   = pdo_fetchall("SELECT * FROM" . tablename('qw_zf_order') . "WHERE uniacid = '$uniacid'" . $con . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize);
        $sql2   = "SELECT * FROM " . tablename('qw_zf_order') . " WHERE uniacid='$uniacid' " . $con;
        $total  = pdo_fetchall($sql2, $params);
        $total  = count($total);
        $pager  = pagination($total, $pindex, $psize);
        include $this->template('order');
    }
}