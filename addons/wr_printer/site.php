<?php
defined('IN_IA') or exit('Access Denied');
session_start();
class Wr_printerModuleSite extends WeModuleSite
{
    public function doWebAds()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebTemplate()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebShow()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebPic()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebConsumecode()
    {
        $this->__web(__FUNCTION__);
    }
    public function doWebConsumecode_add()
    {
        $this->__web(__FUNCTION__);
    }
    public function __web($f_name)
    {
        global $_W, $_GPC;
        checklogin();
        $weid = $_W['uniacid'];
        load()->func('tpl');
        $op = $operation = $_GPC['op'] ? $_GPC['op'] : 'display';
        include_once 'web/' . strtolower(substr($f_name, 5)) . '.php';
    }
    public function doMobilePay()
    {
        global $_W, $_GPC;
        $rid   = intval($_GPC['rid']);
        $price = pdo_fetchcolumn("SELECT price FROM " . tablename('wr_printer') . " WHERE rid = '{$rid}'");
        if ($price <= 0) {
            message('支付错误, 金额不能小于等于0');
        }
        $insert  = array(
            'rid' => $rid,
            'weid' => $_W['uniacid'],
            'consumecode' => random(6, true),
            'status' => 0,
            'stype' => 3,
            'create_time' => time()
        );
        $id      = pdo_insert('wr_printer_consumecode', $insert);
        $ordersn = pdo_insertid();
        $params  = array(
            'tid' => $ordersn,
            'ordersn' => $ordersn,
            'title' => '购买打印照片消费码',
            'fee' => $price
        );
        $this->pay($params);
    }
    public function payResult($params)
    {
        global $_W, $_GPC;
        if ($params['result'] == 'success' && $params['from'] == 'notify') {
            $data = array(
                'openid' => $params['user'],
                'buy_time' => time(),
                'price' => $params['fee']
            );
            pdo_update('wr_printer_consumecode', $data, array(
                'id' => $params['tid']
            ));
        }
        $consumecode = pdo_fetchcolumn("SELECT consumecode FROM " . tablename('wr_printer_consumecode') . " WHERE id = " . $params['tid']);
        if ($params['from'] == 'return') {
            if ($params['result'] == 'success') {
                echo ('<font size="+3">支付成功！请复制并记录好您的消费码：' . $consumecode . '</font>');
            } else {
                message('支付失败！', '', 'error');
            }
        }
    }
}