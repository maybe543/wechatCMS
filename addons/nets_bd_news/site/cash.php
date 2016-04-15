<?php
global $_GPC, $_W;


$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
$uid=$_W["uid"];
$uniacid=$_W['uniaccount']['uniacid'];
//查询条件
$condition = ' uniacid=:uniacid';
$pars=array();
$pars['uniacid']=$uniacid;
$sql="select * from ".tablename('netsbd_set')." where ".$condition;
$set=pdo_fetch($sql,$pars);
if($operation=='display'){

}
if($operation=='ing'){
	$r=pdo_fetch("SELECT c.* FROM ims_netsbd_user_exchange_cash AS c WHERE c.id=:id ORDER BY c.id DESC",array(":id"=>$_GPC['id']));
	$r["state"]="1";
	pdo_update("netsbd_user_exchange_cash",$r,array("id"=>$r["id"]));
}
if($operation=='complate'){
	$r=pdo_fetch("SELECT c.* FROM ims_netsbd_user_exchange_cash AS c WHERE c.id=:id ORDER BY c.id DESC",array(":id"=>$_GPC['id']));
	$r["state"]="2";
	$i=pdo_update("netsbd_user_exchange_cash",$r,array("id"=>$r["id"]));
	if($i>0){
		if($r["cash_type"]==2){
			$member_id=$r["uid"];
			//print("".$member_id);
			$openid=pdo_fetchcolumn("SELECT openid FROM ims_mc_mapping_fans WHERE uid=:uid",array(":uid"=>$member_id));
			//print("-".$openid);
			payWeixin($openid,$r["cash"],$set["mchid"]);
		}
	}
}
$record=pdo_fetchall("SELECT c.*,m.realname,m.nickname,m.avatar,m.alipay FROM ims_netsbd_user_exchange_cash AS c LEFT JOIN ims_mc_members AS m ON m.uid=c.uid WHERE c.uniacid=:uniacid ORDER BY c.id DESC",array(":uniacid"=>$uniacid));

//微信向用户付款  BEGIN
function payWeixin($openid,$money,$mchid){
	
	global $_GPC, $_W;
	$uniacid=$_W['uniaccount']['uniacid'];
	
	//测试
	$wxconfig=get_wxconfig($mchid);
	$settings["appid"]=$wxconfig['appid'];
	$settings["appsecret"]=$wxconfig['appsecret'];
	$settings["mchid"]=$wxconfig['mchid'];
	$settings["uniacid"]=$_W['uniaccount']['uniacid'];
	$settings['password']=$wxconfig['password'];
	$settings['tj_amount']=$money*100; //这里要转换成分
	$toUser=$openid;//"onwnCvmcr8-_uDCa2BPLzC4xX3Es";//测试的openid
	$settings['ip']=$wxconfig['ip'];
	$settings['password']=$wxconfig['password'];
	$result=sendhb($settings,$toUser);
	return $result;
}
/**
* 获取服务器端IP地址
 * @return string
 */
function get_server_ip() { 
    if (isset($_SERVER)) { 
        if($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR']; 
        } else { 
            $server_ip = $_SERVER['LOCAL_ADDR']; 
        } 
    } else { 
        $server_ip = getenv('SERVER_ADDR');
    } 
    return $server_ip; 
}
function get_wxconfig($mchid=""){
	global $_GPC, $_W;
	$uniacid=$_W['uniaccount']['uniacid'];
	$setting = uni_setting($uniacid, array('payment', 'recharge'));
	$pay = $setting['payment'];
	//var_dump($pay);
	$config_sql="SELECT uniacid,`key`,secret,`password` FROM ims_account_wechats WHERE uniacid=:uniacid";
	$wxconfig1=pdo_fetch($config_sql,array("uniacid"=>$uniacid));
	$wxconfig['appid']=$wxconfig1['key'];//"wx2b649dd8aa041807"; //公众号appid
	$wxconfig['appsecret']=$wxconfig1['secret'];//"1e458cc7d8ec1f11ebb760950b6c577d";//公众号密钥
	$wxconfig['mchid']=$mchid;//"1249322801"; //公众号商户号
	$wxconfig['ip']=get_server_ip();//服务器IP
	$wxconfig['password']=$pay['wechat']['signkey'];//'WtQ400zd6c0Y055QMFdiD0Oc2yGk5Cm6';//公众号支付密钥
	/*
	$wxconfig['appid']="wx2b649dd8aa041807"; //公众号appid
	$wxconfig['appsecret']="1e458cc7d8ec1f11ebb760950b6c577d";//公众号密钥
	$wxconfig['mchid']="1249322801"; //公众号商户号
	$wxconfig['ip']="182.254.152.121";//服务器IP
	$wxconfig['password']='WtQ400zd6c0Y055QMFdiD0Oc2yGk5Cm6';//公众号支付密钥
	*/
	//var_dump($wxconfig);
	return $wxconfig;
}
/*
 * 企业微信打款给微信用户
 */
function sendhb($settings,$toUser){
	global $_GPC, $_W;
	define('MB_ROOT', IA_ROOT . '/attachment/fytcert');//定义的微信支付证书路径
	load()->func('communication');
	if (empty($settings['tj_amount'])){
		return;
	}
	$amount=$settings['tj_amount'];
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
	$pars = array();
	$pars['mch_appid'] =$settings['appid'];
	$pars['mchid'] = $settings['mchid'];
	$pars['nonce_str'] = random(32);
	$pars['partner_trade_no'] = random(10). date('Ymd') . random(3);
	$pars['openid'] =$toUser;
	$pars['check_name'] = "NO_CHECK";
	$pars['amount'] =$amount;
	$pars['desc'] = "佣金奖励";
	$pars['spbill_create_ip'] =$settings['ip'];
	ksort($pars, SORT_STRING);
	$string1 = '';
	foreach($pars as $k => $v) {
		$string1 .= "{$k}={$v}&";
	}
	$string1 .= "key={$settings['password']}";
    $pars['sign'] = strtoupper(md5($string1));
    $xml = array2xml($pars);
    $extras = array();
    $extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.7';
    $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.7';
    $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.7';
	if(!empty($settings["uniacid"])){
		$extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.'.$settings["uniacid"];
		$extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.'.$settings["uniacid"];
		$extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.'.$settings["uniacid"];
	}
	$procResult = null;
	$resp = ihttp_request($url, $xml, $extras);
	if(is_error($resp)){
		$procResult = $resp;
    } else {
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
		$dom = new DOMDocument();
        if($dom->loadXML($xml)) {
			$xpath = new DOMXPath($dom);
			$code = $xpath->evaluate('string(//xml/return_code)');
			$ret = $xpath->evaluate('string(//xml/result_code)');
			if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
				$procResult = true;
            } else {
				$error = $xpath->evaluate('string(//xml/err_code_des)');
                $procResult = error(-2, $error);
            }
        } else {
			$procResult = error(-1, 'error response');
        }
		//var_dump($procResult);
    }
	return $procResult;
}
//微信向用户付款  END
include $this->template('cash');
?>