<?php
defined('IN_IA') or exit('Access Denied');
global $_W,$_GPC;

$openid = $_W['openid'];

if(!$openid){
	//非微信进入
	echo "<script>alert('请使用微信扫码！')</script>";
	exit();
}

if(sendPacket() == "ok"){
	echo "裂变红包发放成功！请查收！";
} else{
	echo "裂变红包发放失败！";
}



function  sendPacket(){



	global  $_W;
	$openid = $_W['openid'];
// 	$sql = 'select * from '.tablename('ice_hdredpacket_sendlist').' where openid = :openid and status = :status ORDER BY id desc limit 0,1';
// 	$data = pdo_fetchall($sql,array(':openid' =>$openid ,':status' => '0'));
		
	$result = array();
// 	if(!$data){
// 		$result['content'] = "您已经没有可以发放的奖品了！";
// 		$result['type'] = "error";
// 		return $result; 
// 	}
	
		
// 			$money = $value['money'];
// 			$credit = $value['credit'];
// 			$sign = $value['sign'];

				
				$result = sendRedpack($openid);
				
				return $result;
		
	
		
}
	
	
	
function sendRedpack($param_openid,$money,$sign,$credit){
	global $_W, $_GPC;
	// $param = $this->getDataArray();
	// if(!$param)
	// 	exit();
	load()->func("logging");
	
	$result = array();
	$modulelist = uni_modules(false);
	$name = 'ice_hdgrouphongbao';
	$module = $modulelist[$name];
	if(empty($module)) {
		message('抱歉，你操作的模块不能被访问！');
	}
	define('CRUMBS_NAV', 1);
	$ptr_title = '参数设置';
	$module_types = module_types();
	define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
	
	$settings = $module['config'];
	
		define('ROOT_PATH', dirname(__FILE__));
		define('DS', DIRECTORY_SEPARATOR);
		define('SIGNTYPE', "sha1");
		define('PARTNERKEY',$settings['partner']);
		define('APPID',$settings['appid']);
		define('apiclient_cert',$settings['apiclient_cert']);
		define('apiclient_key',$settings['apiclient_key']);
		define('rootca',$settings['rootca']);
		logging_run('','','get1');
		$mch_billno = $settings['mchid'].date('YmdHis').rand(1000, 9999);//订单号
		logging_run('','','get2');
		//include_once(ROOT_PATH.DS.'pay'.DS.'WxHongBaoHelper.php');
		include_once(IA_ROOT.'/addons/ice_hdscan/pay/WxGroupHongBaoHelper.php');
		//include_once(MODULE_ROOT.'/pay'.DS.'WxHongBaoHelper.php');
		$commonUtil = new CommonUtil();
		
		$wxHongBaoHelper = new WxGroupHongBaoHelper();
		logging_run('','','get3');
						
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
		logging_run('','','get4');
		$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
		$wxHongBaoHelper->setParameter("mch_id", $settings['mchid']);//商户号
		$wxHongBaoHelper->setParameter("wxappid", $settings['appid']);
		$wxHongBaoHelper->setParameter("send_name", $settings['send_name']);//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $param_openid);//相对于医脉互通的openid
		$wxHongBaoHelper->setParameter("total_amount", 400);//付款金额，单位分
		$wxHongBaoHelper->setParameter("total_num", 3);//红包发放总人数
		$wxHongBaoHelper->setParameter("amt_type", "ALL_RAND");//红包发放总人数
		$wxHongBaoHelper->setParameter("wishing", $settings['wishing']);//红包祝福诧
		$wxHongBaoHelper->setParameter("act_name", $settings['act_name']);//活劢名称
		$wxHongBaoHelper->setParameter("remark", $settings['remark']);//备注信息
		
// 		function check_sign_parameters(){
// 			if($this->parameters["nonce_str"] == null ||
// 					$this->parameters["mch_billno"] == null ||
// 					$this->parameters["mch_id"] == null ||
// 					$this->parameters["wxappid"] == null ||
// 					$this->parameters["nick_name"] == null ||
// 					$this->parameters["send_name"] == null ||
// 					$this->parameters["re_openid"] == null ||
// 					$this->parameters["total_amount"] == null ||
// 					$this->parameters["max_value"] == null ||
// 					$this->parameters["total_num"] == null ||
// 					$this->parameters["wishing"] == null ||
// 					$this->parameters["client_ip"] == null ||
// 					$this->parameters["act_name"] == null ||
// 					$this->parameters["remark"] == null ||
// 					$this->parameters["min_value"] == null
	
// 		$wxHongBaoHelper->setParameter("logo_imgurl", "https://www.baidu.com/img/bdlogo.png");//商户logo的url
// 		$wxHongBaoHelper->setParameter("share_content", '分享文案测试');//分享文案
// 		$wxHongBaoHelper->setParameter("share_url", "http://baidu.com");//分享链接
// 		$wxHongBaoHelper->setParameter("share_imgurl", "http://avatar.csdn.net/1/4/4/1_sbsujjbcy.jpg");//分享的图片url
		
		logging_run('','','get5');
	$postXml = $wxHongBaoHelper->create_hongbao_xml();
// 	$postXml = '';
// 	$postXml .= '<xml>';
// 	$postXml .= '<sign><![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]></sign>';
// 	$postXml .= '<mch_billno><![CDATA[1243564202201507091509503442]]></mch_billno>';
// 	$postXml .= '<mch_id><![CDATA[1243564202]]></mch_id>';
// 	$postXml .= '<wxappid><![CDATA[wx14235ffa28263541]]></wxappid>';
// 	$postXml .= '<send_name><![CDATA[冰点会员裂变红包]]></send_name>';
// 	$postXml .= '<re_openid><![CDATA[osMEksx_1RKB8GQQIOxO96YWzsg0]]></re_openid>';
// 	$postXml .= '<total_amount><![CDATA[400]]></total_amount>';
// 	$postXml .= '<amt_type><![CDATA[ALL_RAND]]></amt_type>';
// 	$postXml .= '<total_num><![CDATA[3]]></total_num>';
// 	$postXml .= '<wishing><![CDATA[冰点会员1]]></wishing>';
// 	$postXml .= '<act_name><![CDATA[冰点会员]]></act_name>';
// 	$postXml .= '<remark><![CDATA[新年红包]]></remark>';
// 	$postXml .= '<nonce_str><![CDATA[50780e0cca98c8c8e814883e5caa672e]]></nonce_str>';
// 	$postXml .= '</xml>';
	
	
	logging_run($postXml,'','get6');
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		
	logging_run('','','get7');
	$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	logging_run('','','get8');
	$return_code=$responseObj->return_code;
	$result_code=$responseObj->result_code;
	
	
	logging_run(json_encode($responseObj),'','responseObj2');
	logging_run($return_code.':'.$result_code,'','responseObj1');
	logging_run($responseXml,'','responseObj3');
	if($return_code=='SUCCESS'){
		if($result_code=='SUCCESS'){

				return "ok";
		}else{
			if($responseObj->err_code=='NOTENOUGH'){
				$result['content'] =  "后台繁忙，请稍后再试！";
				$result['type'] = 'error';
				return $result;
			}else if($responseObj->err_code=='TIME_LIMITED'){
				$result['content'] =  "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
				$result['type'] = 'error';
				return $result;
			}else if($responseObj->err_code=='SYSTEMERROR'){
				$result['content'] =  "系统繁忙，请稍后再试！";
				$result['type'] = 'error';
				return $result;
			}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
				$result['content'] =  "今日红包已达上限，请明日再试！";
				$result['type'] = 'error';
				return $result;
			}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
				$result['content'] =  "每分钟红包已达上限，请稍后再试！";
				$result['type'] = 'error';
				return $result;
			}
				
			$result['content'] =  "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
			$result['type'] = 'error';
			return $result;
		}
	}
	if($return_code== 'FAIL'){
		$result['content'] =  $responseObj->return_msg;
		$result['type'] = 'error';
		return $result;
	}
}
	
	
function GetCardNo() {
	global $_W;
	//获取会员卡号
	$sql = 'SELECT memCardNo from '.tablename('ice_hdusers')." where uniacid = :uniacid order by memCardNo DESC";
	$cardNo = pdo_fetchcolumn($sql,array(":uniacid"=>$_W['uniacid']));
	$result =1;
	if(isset($cardNo))
	{
		$result =intval($cardNo)+1;
	}
	return $result;
}









