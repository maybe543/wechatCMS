<?php
/**
 * 微票务模块微站定义
 *
 * @author hawk
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('HK_ROOT', IA_ROOT . '/addons/hawk_ticket');

class Hawk_ticketModuleSite extends WeModuleSite {

	public function doMobileTicket() {
		//这个操作被定义用来呈现 功能封面
	}
	protected function checkMobile(){
//		global $_W,$_GPC;
//		if(empty($_W['fans']['from_user'])){
//			message('访问错误','','error');
//			exit();
//		}
//		load()->model('mc');
//		$fansinfo = mc_fansinfo($_W['fans']['from_user']);
//		if($fansinfo['follow']!=1){
//			$url = $this->module['config']['follow'];
//			header("Location:{$url}");
//			exit();
//		}
	}
	public function doWebEntry() {
		include $this->template('entry');
	}
	public function doWebQr() {
		global $_GPC;
		$raw = @base64_decode($_GPC['raw']);
		if(!empty($raw)) {
			include IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode::png($raw, false, QR_ECLEVEL_Q, 4);
		}
	}
	public function doMobileQr() {
		global $_GPC;
		$raw = @base64_decode($_GPC['raw']);
		if(!empty($raw)) {
			include IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
			QRcode::png($raw, false, QR_ECLEVEL_Q, 4);
		}
	}

	public function doMobilePay() {
		global $_W,$_GPC;
		$pars = unserialize(base64_decode($_GPC['pars']));
		$fee = floatval($pars['fee']);
		if($fee <= 0) {
			message('支付错误, 金额小于0');
		}
		$params = array(
			'tid' => $pars['id'],
			'ordersn' => TIMESTAMP,
			'title' => $pars['title'],
			'fee' => $fee,
			'user' => $_W['member']['uid'],
		);
		$this->pay($params);
	}

	public function payResult($params) {
		global $_W;
		require_once(MODULE_ROOT.'/module/Order.class.php');
		require_once(MODULE_ROOT.'/module/Activity.class.php');
		$order = new Order();
		$act   = new Activity();
		if ($params['result'] == 'success') {
			$update = array();
			$update['type'] = $params['type'];
			$update['status'] = 2;
			$update['paytime'] = TIMESTAMP;
			$id = $params['tid'];
			$res = $order->modify($id,$update);
			//查询订单
			$orderinfo = $order->getOne($id);
			$actinfo   = $act->getOne($orderinfo['actid']);

			if($res){
				if (!empty($this->module['config']['template'])) {
					$good = $actinfo['proname'];
					$price = $orderinfo['fee'];
					switch($params['type']){
						case 'credit':
							$paytype='余额支付';
							break;
						case 'wechat':
							$paytype='微信支付';
							break;
						case 'unionpay':
							$paytype='银联支付';
							break;
						case 'baifubao':
							$paytype='百度钱包支付';
							break;
						case 'delivery':
							$paytype='货到付款';
							break;
						case 'line':
							$paytype='线下汇款';
							break;
					}
					$data = array (
						'first' => array('value' => '恭喜您订购成功'),
						'keyword1' => array('value' => date('Y-m-d H:i',strtotime('now'))),
						'keyword2' => array('value' => $good),
						'keyword3' => array('value' => $price."元"),
						'keyword4' => array('value' => $_W['fans']['nickname']),
						'keyword5' => array('value' => $paytype)
					);
					$acc = WeAccount::create($_W['acid']);
					$acc->sendTplNotice($_W['fans']['from_user'],$this->module['config']['templateid'],$data);
				}
			}

		}
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				message('支付成功！',$this->createMobileUrl('myorder'), 'success');
			} else {
				message('支付失败！', $this->createMobileUrl('list'), 'error');
			}
		}
	}


}