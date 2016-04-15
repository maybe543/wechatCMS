<?php
/**
 * 超级赚模块微站定义
 *
 * @author hawk
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('HK_ROOT', IA_ROOT . '/addons/hawk_surpermoney');

class Hawk_surpermoneyModuleSite extends WeModuleSite {

	public function doMobileMoney() {
		//这个操作被定义用来呈现 功能封面
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
	//积分
	protected function creditsend($record,$type='1'){
		require_once HK_ROOT . '/module/Cashrecord.class.php';
		require_once HK_ROOT . '/module/Fan.class.php';
		$cashrc = new Cashrecord();
		global $_W;
		load()->model('mc');
		$uid = mc_openid2uid($record['openid']);
		if(!$uid){
			return error(-2,'用户信息错误');
		}else{
			$record['uid'] = $uid;
		}
		if($type=='1'){
			$result = mc_credit_update($record['uid'], 'credit1', $record['money'],array($_W['uid'],'超级赚提现积分增加'));
		}elseif($type=='2'){
			$result = mc_credit_update($record['uid'], 'credit2', $record['money'],array($_W['uid'],'超级赚提现余额增加'));
		}
		if($result == true){
			$input = array();
			$input['openid'] = $record['openid'];
			$input['money']  = $record['money']*100;
			$cashrc->create($input);
			$fan = new Fan();
			$fandata = $fan->getOne($record['openid']);
			$update= array();
			$update['used'] = $fandata['used'] + $record['money']*100;
			$res = $fan->modify($record['openid'],$update);
			if ($res) {
				return true;
			} else {
				return error(-2,"记录信息错误");
			}
		}
	}
	protected function balancesend($record){

	}
	protected function send($record)
	{
		//$record['id'] $record['openid'] $record['money'];
		global $_W;
		require_once HK_ROOT . '/module/Cashrecord.class.php';
		require_once HK_ROOT . '/module/Fan.class.php';
		$cashrc = new Cashrecord();
		$diy = array();
		$diy['nick_name'] = $_W['account']['name'];
		$diy['send_name'] = $_W['account']['name'];
		$diy['wishing'] = '恭喜发财';
		$diy['act_name'] = '超级赚提现';
		$diy['remark'] = '超级赚提现';
		$diy['logo_imgurl'] = './addons/static/img/share.jpg';
		$diy['share_content'] = '超级赚提现';
		$diy['share_imgurl'] = $diy['logo_imgurl'];
		$diy['share_url'] = $_W['siteroot'] . 'app/' . substr($this->createMobileUrl('entry'), 2);

		$uniacid = $_W['uniacid'];
		$api = $this->module['config']['api'];
		if (empty($api)) {
			return error(-2, '系统还未开放');
		}
		$fee = floatval($record['money']);
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$pars = array();
		$pars['nonce_str'] = random(32);
		$pars['mch_billno'] = $api['mchid'] . date('Ymd') . sprintf('%010d', $record['id']);
		$pars['mch_id'] = $api['mchid'];
		$pars['wxappid'] = $api['appid'];
		$pars['nick_name'] = $diy['nick_name'];
		$pars['send_name'] = $diy['send_name'];
		$pars['re_openid'] = $record['openid'];
		$pars['total_amount'] = $fee;
		$pars['min_value'] = $pars['total_amount'];
		$pars['max_value'] = $pars['total_amount'];
		$pars['total_num'] = 1;
		$pars['wishing'] = $diy['wishing'];
		$pars['client_ip'] = $api['ip'];
		$pars['act_name'] = $diy['act_name'];
		$pars['remark'] = $diy['remark'];
		$pars['logo_imgurl'] = tomedia($diy['logo_imgurl']);
		$pars['share_content'] = $diy['share_content'];
		$pars['share_imgurl'] = tomedia($diy['share_imgurl']);
		$pars['share_url'] = $diy['share_url'];
		ksort($pars, SORT_STRING);
		$string1 = '';
		foreach ($pars as $k => $v) {
			$string1 .= "{$k}={$v}&";
		}
		$string1 .= "key={$api['password']}";
		$pars['sign'] = strtoupper(md5($string1));
		$xml = array2xml($pars);
		$extras = array();
		$extras['CURLOPT_CAINFO'] = HK_ROOT . '/cert/rootca.pem.' . $uniacid;
		$extras['CURLOPT_SSLCERT'] = HK_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
		$extras['CURLOPT_SSLKEY'] = HK_ROOT . '/cert/apiclient_key.pem.' . $uniacid;

		load()->func('communication');
		$procResult = null;
		$resp = ihttp_request($url, $xml, $extras);
		if (is_error($resp)) {
			$procResult = $resp;
		} else {
			$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
			$dom = new \DOMDocument();
			if ($dom->loadXML($xml)) {
				$xpath = new \DOMXPath($dom);
				$code = $xpath->evaluate('string(//xml/return_code)');
				$ret = $xpath->evaluate('string(//xml/result_code)');
				if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
					$procResult = true;
				} else {
					$error = $xpath->evaluate('string(//xml/err_code_des)');
					$procResult = error(-2, $error);
				}
			} else {
				$procResult = error(-1, 'error response');
			}
		}

		if (is_error($procResult)) {
			$input = array();
			$input['openid'] = $record['openid'];
			$input['money']  = 0;
			$cashrc->create($input);
			return error(-1,$procResult['message']);
		} else {
			$input = array();
			$input['openid'] = $record['openid'];
			$input['money']  = $record['money'];
			$cashrc->create($input);
			$fan = new Fan();
			$fandata = $fan->getOne($record['openid']);
			$update= array();
			$update['used'] = $fandata['used'] + $record['money'];
			$res = $fan->modify($record['openid'],$update);
			if ($res) {
				return true;
			} else {
				return error(-2,"记录信息错误");
			}
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
}