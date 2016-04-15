<?php
global $_W,$_GPC;
require_once HK_ROOT . '/module/Fan.class.php';
require_once HK_ROOT . '/module/Cashrecord.class.php';
$send = $_GPC['send'];
$money = $_GPC['money'];
//提现强制关注
//echo "<pre>";
//print_r($_W);
//echo "</pre>";
if($_W['fans']['follow']==0 || empty($_W['fans']['follow'])){
    $hurl = $this->module['config']['api']['follow'];
    message('您还未关注，必须关注才能提现',$hurl,'info');
}
//最低提现
$lowmoney = $this->module['config']['api']['low'];
$lowmoney = $lowmoney/100;
$fan = new Fan();
$cashrecord = new Cashrecord();
$fandata = $fan->getOne($_W['fans']['from_user']);
if($fandata){
    $allmoney = $fandata['credit']/100;
    $allowmoney = ($fandata['credit'] - $fandata['used'])/100;
    if($allowmoney < 0){
        $allowmoney = 0;
    }
    $usedmoney  = ($fandata['used'])/100;
    if(!empty($money) && !empty($send)){
        $record = array();
        $record['id'] = $cashrecord->getmaxid();
        $record['openid'] = $_W['fans']['from_user'];
        $record['money']  = $allowmoney*100;
        //$record['money'] = 50;
        $low = $this->module['config']['api']['low'] ? $this->module['config']['api']['low'] : 200;
        if($record['money'] < $low){
            message("少于最低限额不能提现",$this->createMobileUrl('user'),'warning');
        }
        //提现类型处理
        $type = $this->module['config']['api']['type'];
        if($type=='0'){
          //红包
            //print_r($record);
            //exit();
            $res = $this->send($record);
        }elseif($type=='1'){
          //积分
          $record['money'] = intval($allowmoney);
          $res = $this->creditsend($record);

        }elseif($type=='2'){
          //余额
          $record['money'] = intval($allowmoney);
          $res = $this->creditsend($record,'2');
        }
        if(is_error($res)){
            message($res['message'],$this->createMobileUrl('user'),'warning');
        }
    }

}
include $this->template('cash');