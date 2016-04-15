<?php
global  $_W,$_GPC;




$openid = $_W['openid'];
if(empty($openid))
	exit();

$code = $_GPC['code'];


$count = pdo_fetchcolumn("select count(*) from ".tablename("ice_yzmhb_code")." where uniacid = :uniacid and  code = :code and status = 2",array(':code'=>$code,':uniacid'=>$_W['uniacid']));


if($count == 0){
	echo "UNHAS";
}else{
	$money = rand(0, 100);//已分为单位
	$res = sendPacket($openid,$money);
	if($res == "ok"){
		echo "ok";
	}	
}









function sendRedpack($openid,$money){
	global $_W, $_GPC;
	// $param = $this->getDataArray();
	// if(!$param)
	// 	exit();
	$result = array();
	$modulelist = uni_modules(false);
	$name = 'ice_yzmhb';
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

		$mch_billno = $settings['mchid'].date('YmdHis').rand(1000, 9999);//订单号

		//include_once(ROOT_PATH.DS.'pay'.DS.'WxHongBaoHelper.php');
		include_once(IA_ROOT.'/addons/ice_hdscan/pay/WxHongBaoHelper.php');
		//include_once(MODULE_ROOT.'/pay'.DS.'WxHongBaoHelper.php');
		$commonUtil = new CommonUtil();
		$wxHongBaoHelper = new WxHongBaoHelper();
							
						
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
		$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
		$wxHongBaoHelper->setParameter("mch_id", $settings['mchid']);//商户号
		$wxHongBaoHelper->setParameter("wxappid", $settings['appid']);
		//$wxHongBaoHelper->setParameter("nick_name", $settings['nick_name']);//提供方名称
		//$wxHongBaoHelper->setParameter("send_name", $settings['send_name']);//红包发送者名称
		$wxHongBaoHelper->setParameter("nick_name", "随州市冰点网络");//提供方名称
		$wxHongBaoHelper->setParameter("send_name", "随州市冰点网络");//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $openid);//相对于医脉互通的openid
		$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
		$wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
		$wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
		$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
	//	$wxHongBaoHelper->setParameter("wishing", $settings['wishing']);//红包祝福诧
		$wxHongBaoHelper->setParameter("wishing", "恭喜恭喜");//红包祝福诧
		$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
	//	$wxHongBaoHelper->setParameter("act_name", $settings['act_name']);//活劢名称
		$wxHongBaoHelper->setParameter("act_name", "二维码红包");//活劢名称
	//	$wxHongBaoHelper->setParameter("remark", $settings['remark']);//备注信息
		$wxHongBaoHelper->setParameter("remark", "恭喜获得二维码红包");//备注信息
	
		$wxHongBaoHelper->setParameter("logo_imgurl", "https://www.baidu.com/img/bdlogo.png");//商户logo的url
	//	$wxHongBaoHelper->setParameter("share_content", '分享文案测试');//分享文案
	//	$wxHongBaoHelper->setParameter("share_url", "http://baidu.com");//分享链接
	//	$wxHongBaoHelper->setParameter("share_imgurl", "http://avatar.csdn.net/1/4/4/1_sbsujjbcy.jpg");//分享的图片url
		
		
	$postXml = $wxHongBaoHelper->create_hongbao_xml();
	$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
	$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		
	
	$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		
	$return_code=$responseObj->return_code;
	$result_code=$responseObj->result_code;
		
	if($return_code=='SUCCESS'){
		if($result_code=='SUCCESS'){
			$result['type'] = "ok";
			return $result;
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