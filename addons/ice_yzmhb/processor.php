<?php
/**
 * 验证码红包模块处理程序
 *
 * @author 宙斯
 * @url #
 */
defined('IN_IA') or exit('Access Denied');

class Ice_yzmhbModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		global $_W, $_GPC;
		//这里定义此模块进行消息处理时的具体过程, 请查看微赞文档来编写你的代码
		$openid = $_W['openid'];
		if(strlen($content) <= 2 || substr($content, 0,2) != "hb"){
		return $this->respText("对不起,验证码错误！");
		}
		
		$code = substr($content, 2);
		
		$res = pdo_fetch("select count(*) as count,id,yzmhbid,piciid,type  from ".tablename("ice_yzmhb_code")." where uniacid = :uniacid and  code = :code and status = 1  and yzmhbid = 0",array(':code'=>$code,':uniacid'=>$_W['uniacid']));
		$count = $res['count'];
		$cid = $res['id'];
		$piciid = $res['piciid'];
		$type = $res['type'];
		$yzmhbid = $res['yzmhbid'];
		if($count == 0){
			return $this->respText("对不起,验证码错误！或者已经被使用");
		}
		
		
		//判断type
	if($type == 1){
		
		
	$settings = $this->getcommonhbsettings();
		$prizes = pdo_fetchall("select * from".tablename("ice_yzmhb_prize")." where uniacid = :uniacid and type = 1 order by money desc",array(":uniacid"=>$_W['uniacid']));
		
		if(empty($prizes)){
			$money = $settings['commonmoney'];
		}else{
			
			foreach ($prizes as $key => $val) {
				$arr[$val['id']] = $val['prizeodds'];
			}
		
			$rid = $this->get_rand($arr);
		
			$res1 = pdo_fetch("select money,prizesum from ".tablename("ice_yzmhb_prize")." where id = :id",array(":id"=>$rid));
		
			$prizesum = $res1['prizesum'];
			$money = $res1['money'];
		
			if($prizesum <= 0){
				$res2 = pdo_fetch("select id,money from ".tablename("ice_yzmhb_prize")." where uniacid = :uniacid and type = 1 order by money limit 1",array(":uniacid"=>$_W['uniacid']));
				$rid = $res2['id'];
				$money = $res2['money'];
			}
		
		}
// 	$money = $settings['commonmoney'];
	//$money = rand(100, 200);//以分为单位
	//echo $money;
		$data = array(
				'uniacid'=>$_W['uniacid'],
				'codeid' => $cid,
				'openid' => $openid,
				'yzmhbid' => $yzmhbid,
				'money' => $money,
				'type' => $type,
				'status' => '2',
				'time' => time()
		);
		pdo_insert("ice_yzmhb_sendlist",$data);
		$sid = pdo_insertid();
		pdo_query("update ".tablename("ice_yzmhb_prize")." set prizesum = prizesum - 1 where id = :id",array(":id"=>$rid));
		pdo_query("update ".tablename("ice_yzmhb_codenum")." set usedcount = usedcount + 1 where id = :id",array(":id"=>$piciid));
		pdo_update("ice_yzmhb_code",array("status"=>2,"openid"=>$openid),array("id"=>$cid));
	$res = $this->sendCommonRedpack($openid, $settings,$money);
	if($res['type'] == "ok"){
		pdo_update("ice_yzmhb_sendlist",array("status"=>'1'),array("id"=>$sid));
		return $this->respText("您的红包已经发放请注意查收");//服务器忙
	}else{
		
			return $this->respText("由于系统问题您的红包未能正常发放，请联系客服！");//服务器忙
		
	}
	
	}else if($type == 2){
		$settings = $this->getgrouphbsettings();
		
		
		$prizes = pdo_fetchall("select * from".tablename("ice_yzmhb_prize")." where uniacid = :uniacid and type = 2 order by money desc",array(":uniacid"=>$_W['uniacid']));
		
		if(empty($prizes)){
			$money = $settings['groupmoney'];
			$totalmember = $settings['groupnum'];
		}else{
		
			foreach ($prizes as $key => $val) {
				$arr[$val['id']] = $val['prizeodds'];
			}
		
			$rid = $this->get_rand($arr);
		
			$res1 = pdo_fetch("select money,prizesum,prizename from ".tablename("ice_yzmhb_prize")." where id = :id",array(":id"=>$rid));
		
			$prizesum = $res1['prizesum'];
			$money = $res1['money'];
			$totalmember = $res1['prizename'];
			if($prizesum <= 0){
				$res2 = pdo_fetchcolumn("select id,money,prizename from ".tablename("ice_yzmhb_prize")." where uniacid = :uniacid and type = 1 order by money limit 1",array(":uniacid"=>$_W['uniacid']));
				$rid = $res2['id'];
				$money = $res1['money'];
				$totalmember = $res1['prizename'];
			}
		
		}
		
		
			$data = array(
					'uniacid'=>$_W['uniacid'],
					'codeid' => $cid,
					'openid' => $openid,
					'yzmhbid' => $yzmhbid,
					'money' => $money,
					'totalmember' => $totalmember,
					'type' => $type,
					'status' => '2',
					'time' => time()
			);
			pdo_insert("ice_yzmhb_sendlist",$data);
			$sid = pdo_insertid();
			pdo_query("update ".tablename("ice_yzmhb_prize")." set prizesum = prizesum - 1 where id = :id",array(":id"=>$rid));
			pdo_query("update ".tablename("ice_yzmhb_codenum")." set usedcount = usedcount + 1 where id = :id",array(":id"=>$piciid));
			pdo_update("ice_yzmhb_code",array("status"=>2,"openid"=>$openid),array("id"=>$cid));
		$res = $this->sendGroupRedpack($openid,$settings,$money,$totalmember);
		if($res['type'] == "ok"){
			pdo_update("ice_yzmhb_sendlist",array("status"=>'1'),array("id"=>$sid));
			return $this->respText("恭喜您获得一个裂变红包，注意查收！");//服务器忙
		}else{
			
			return $this->respText("由于系统问题您的红包未能正常发放，请联系客服！");
		
	}
		
	}else if ($type == 3){
		
		$settings = $this->getguesshbsettings();

		//判断是否验证码是不是已经兑换 或者已经废弃
		$res1 = pdo_fetch("select count(*) as count , status,openid from ".tablename("ice_guesshb")." where uniacid = :uniacid and  codeid = :codeid",array(":uniacid"=>$_W['uniacid'],":codeid"=>$cid));
		$count = $res1['count'];
		$status = $res1['status'];
		if($count > 0){
			if($status == '2' || $status == '3'){
				return $this->respText("对不起,验证码错误！或者已经被使用");
				exit();
			}else if($status == '1'){
				$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&codeid='.$cid.'&do=share&m=ice_guesshb';
				
				$sharephoto = $settings['sharephoto'];
				if(substr($sharephoto, 0 , 5) != "http:"){
					$sharephoto = $_W['siteroot']."attachment/".$sharephoto;
				}
				$news =  array(array('title' =>'验证码猜红包' , 'description' => '验证码猜红包','picurl' =>$sharephoto,'url' =>$url ));
				return $this->respNews($news);
			}
		}else{
		
			$moneyScope = $settings['moneyScope'];
			$ms = explode("-", $moneyScope);
			$min = $ms['0'];
			$max = $ms['1'];
			$money = rand($min, $max);
		
			$data = array(
					'uniacid' => $_W['uniacid'],
					'codeid' =>$cid,
					'openid' => $openid,
					'gettime' => time(),
					'guess_count' => 0,
					'money' => $money,
					'interval' => $moneyScope,
					'hastime' => '',
					'status' => '1'
			);
			if(pdo_insert("ice_guesshb",$data)){
				pdo_update("ice_yzmhb_code",array("openid"=>$openid),array("id" => $cid));
				$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&codeid='.$cid.'&do=share&m=ice_guesshb';
				
				$sharephoto = $settings['sharephoto'];
				if(substr($sharephoto, 0 , 5) != "http:"){
					$sharephoto = $_W['siteroot']."attachment/".$sharephoto;
				}
				$news =  array(array('title' =>'验证码猜红包' , 'description' => '验证码猜红包','picurl' =>$sharephoto,'url' =>$url ));
				return $this->respNews($news);
			}
		
		}
		
		
	}else if($type == 4){
		
		$settings = $this->getrobhbsettings();

		$res1 = pdo_fetch("select count(*) as count , status,openid from ".tablename("ice_robhb")." where uniacid = :uniacid and  codeid = :codeid",array(":uniacid"=>$_W['uniacid'],":codeid"=>$cid));
		$count = $res1['count'];
		$status = $res1['status'];
		if($count > 0){
			if($status == '2' || $status == '3'){
				return $this->respText("对不起,验证码错误！或者已经被使用");
				exit();
			}else if($status == '1'){
				$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&codeid='.$cid.'&do=share&m=ice_robhb';
				
				$sharephoto = $settings['sharephoto'];
				if(substr($sharephoto, 0 , 5) != "http:"){
					$sharephoto = $_W['siteroot']."attachment/".$sharephoto;
				}
				$news =  array(array('title' =>'验证码抢红包' , 'description' => '验证码抢红包','picurl' =>$sharephoto,'url' =>$url ));
				return $this->respNews($news);
			}
		
		}else{
			$data = array(
					'uniacid' => $_W['uniacid'],
					'codeid' =>$cid,
					'openid' => $openid,
					'gettime' => time(),
					'hastime' => '',
					'status' => '1'
			);
			if(pdo_insert("ice_robhb",$data)){
				$rid = pdo_insertid();
				$data1 = array(
						'uniacid' => $_W['uniacid'],
						'openid' => $openid,
						'rid' => $rid,
						'codeid' => $cid,
						'time' => time()
				);
				pdo_insert("ice_robhb_details",$data1);
				pdo_update("ice_yzmhb_code",array("openid"=>$openid),array("id" => $cid));
				
				$url = $_W['siteroot'].'app/index.php?i='.$_W['uniacid'].'&c=entry&codeid='.$cid.'&do=share&m=ice_robhb';
				
				$sharephoto = $settings['sharephoto'];
				if(substr($sharephoto, 0 , 5) != "http:"){
					$sharephoto = $_W['siteroot']."attachment/".$sharephoto;
				}
				$news =  array(array('title' =>'验证码抢红包' , 'description' => '验证码抢红包','picurl' =>$sharephoto,'url' =>$url ));
				return $this->respNews($news);
			
			}
		
		}
	}else{
		return $this->respText("对不起,验证码错误！或者已经被使用");
	}
		
	}

	private function sendRedpack($param_openid,$money,$packetid){
		global $_W, $_GPC;
		define('ROOT_PATH', dirname(__FILE__));
		define('DS', DIRECTORY_SEPARATOR);
		define('SIGNTYPE', "sha1");
		define('PARTNERKEY',$this->module['config']['partner']);
		define('APPID',$this->module['config']['appid']);		
		define('apiclient_cert',$this->module['config']['apiclient_cert']);
		define('apiclient_key',$this->module['config']['apiclient_key']);
		define('rootca',$this->module['config']['rootca']);
		$commonUtil = new CommonUtil();
		$wxHongBaoHelper = new WxHongBaoHelper();		
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
		$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
		$wxHongBaoHelper->setParameter("mch_id", $param['mchid']);//商户号
		$wxHongBaoHelper->setParameter("wxappid", $param['appid']);

		$wxHongBaoHelper->setParameter("nick_name", '味万金');//提供方名称
		$wxHongBaoHelper->setParameter("send_name", '味万金');//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $param_openid);//openid
		$wxHongBaoHelper->setParameter("total_amount", $money*100);//付款金额，单位分
		$wxHongBaoHelper->setParameter("min_value", $money*100);//最小红包金额，单位分
		$wxHongBaoHelper->setParameter("max_value", $money*100);//最大红包金额，单位分
		$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
		$wxHongBaoHelper->setParameter("wishing", '味万金');//红包祝福诧
		$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
		$wxHongBaoHelper->setParameter("act_name", '味万金红包');//活劢名称
		$wxHongBaoHelper->setParameter("remark", '抢红包了');//备注信息
	
		$wxHongBaoHelper->setParameter("logo_imgurl", "https://www.baidu.com/img/bdlogo.png");//商户logo的url
		$wxHongBaoHelper->setParameter("share_content", '分享文案测试');//分享文案
		$wxHongBaoHelper->setParameter("share_url", "http://baidu.com");//分享链接
		$wxHongBaoHelper->setParameter("share_imgurl", "http://avatar.csdn.net/1/4/4/1_sbsujjbcy.jpg");//分享的图片url
		
		
		$postXml = $wxHongBaoHelper->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		logging_run($responseXml,'','responseXml');
		
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		
		$return_code=$responseObj->return_code;
		$result_code=$responseObj->result_code;
		if($return_code=='SUCCESS'){
			if($result_code=='SUCCESS'){
				$total_amount=$responseObj->total_amount*1.0/100;
				pdo_update('ice_redpacket_sendlist',array('status' =>'1'),array('openid' => $param_openid,'packetid' => $packetid,));
				return "红包发放成功！金额为：".$total_amount."元！拆开发放的红包即可领取红包！";
			}else{		
				
				if($responseObj->err_code=='NOTENOUGH'){
					return "您来迟了，红包已经发完！！！";
				}else if($responseObj->err_code=='TIME_LIMITED'){
					return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
				}else if($responseObj->err_code=='SYSTEMERROR'){
					return "系统繁忙，请稍后再试！";
				}else if($responseObj->err_code=='DAY_OVER_LIMITED'){
					return "今日红包已达上限，请明日再试！";
				}else if($responseObj->err_code=='SECOND_OVER_LIMITED'){
					return "每分钟红包已达上限，请稍后再试！";
				}

				return "红包发放失败！".$responseObj->return_msg."！请稍后再试！";
			}
		}
	}
	
	public function getcommonhbsettings(){
		
		load()->func('tpl');
		//这里来展示设置项表单
		$modulelist = uni_modules(false);
		$name = 'ice_commonhb';
		$module = $modulelist[$name];
		if(empty($module)) {
			message('抱歉，你操作的模块不能被访问！');
		}
		define('CRUMBS_NAV', 1);
		$ptr_title = '参数设置';
		$module_types = module_types();
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
		
		$settings = $module['config'];
		return $settings;
		
	}
	
	public function getgrouphbsettings(){
	
		load()->func('tpl');
		//这里来展示设置项表单
		$modulelist = uni_modules(false);
		$name = 'ice_grouphb';
		$module = $modulelist[$name];
		if(empty($module)) {
			message('抱歉，你操作的模块不能被访问！');
		}
		define('CRUMBS_NAV', 1);
		$ptr_title = '参数设置';
		$module_types = module_types();
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
	
		$settings = $module['config'];
		return $settings;
	
	}
	
	public function getguesshbsettings(){
	
		load()->func('tpl');
		//这里来展示设置项表单
		$modulelist = uni_modules(false);
		$name = 'ice_guesshb';
		$module = $modulelist[$name];
		if(empty($module)) {
			message('抱歉，你操作的模块不能被访问！');
		}
		define('CRUMBS_NAV', 1);
		$ptr_title = '参数设置';
		$module_types = module_types();
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
	
		$settings = $module['config'];
		return $settings;
	
	}
	
	public function getrobhbsettings(){
	
		load()->func('tpl');
		//这里来展示设置项表单
		$modulelist = uni_modules(false);
		$name = 'ice_robhb';
		$module = $modulelist[$name];
		if(empty($module)) {
			message('抱歉，你操作的模块不能被访问！');
		}
		define('CRUMBS_NAV', 1);
		$ptr_title = '参数设置';
		$module_types = module_types();
		define('ACTIVE_FRAME_URL', url('home/welcome/ext', array('m' => $name)));
	
		$settings = $module['config'];
		return $settings;
	
	}
	
	function sendCommonRedpack($openid,$settings,$money){
		global $_W, $_GPC;
		// $param = $this->getDataArray();
		// if(!$param)
		// 	exit();
	
		$result = array();
	
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
		include_once(IA_ROOT.'/addons/ice_commonhb/pay/WxHongBaoHelper.php');
		//include_once(MODULE_ROOT.'/pay'.DS.'WxHongBaoHelper.php');
		$commonUtil = new CommonUtil();
		$wxHongBaoHelper = new WxHongBaoHelper();
	
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
		$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
		$wxHongBaoHelper->setParameter("mch_id", $settings['mchid']);//商户号
		$wxHongBaoHelper->setParameter("wxappid", $settings['appid']);
		$wxHongBaoHelper->setParameter("nick_name", $settings['nick_name']);//提供方名称
		$wxHongBaoHelper->setParameter("send_name", $settings['send_name']);//红包发送者名称
		// 		$wxHongBaoHelper->setParameter("nick_name", "随州市冰点网络");//提供方名称
		// 		$wxHongBaoHelper->setParameter("send_name", "随州市冰点网络");//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $openid);//相对于医脉互通的openid
		$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
		$wxHongBaoHelper->setParameter("min_value", $money);//最小红包金额，单位分
		$wxHongBaoHelper->setParameter("max_value", $money);//最大红包金额，单位分
		$wxHongBaoHelper->setParameter("total_num", 1);//红包发放总人数
		$wxHongBaoHelper->setParameter("wishing", $settings['wishing']);//红包祝福诧
		// 		$wxHongBaoHelper->setParameter("wishing", "恭喜恭喜");//红包祝福诧
		$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
		$wxHongBaoHelper->setParameter("act_name", $settings['act_name']);//活劢名称
		// 		$wxHongBaoHelper->setParameter("act_name", "二维码红包");//活劢名称
		$wxHongBaoHelper->setParameter("remark", $settings['remark']);//备注信息
		// 		$wxHongBaoHelper->setParameter("remark", "恭喜获得二维码红包");//备注信息
	
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
		//return $result_code;
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
	
	
	function  sendGroupRedpack($openid,$settings,$money,$totalmember){
	
		global $_W, $_GPC;
		// $param = $this->getDataArray();
		// if(!$param)
		// 	exit();
		load()->func("logging");
	
		$result = array();
	
	
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
		include_once(IA_ROOT.'/addons/ice_grouphb/pay/WxGroupHongBaoHelper.php');
		//include_once(MODULE_ROOT.'/pay'.DS.'WxHongBaoHelper.php');
		$commonUtil = new CommonUtil();
	
		$wxHongBaoHelper = new WxGroupHongBaoHelper();
		$wxHongBaoHelper->setParameter("nonce_str", $commonUtil->create_noncestr());//随机字符串，不长于32位
		$wxHongBaoHelper->setParameter("mch_billno", $mch_billno);//订单号
		$wxHongBaoHelper->setParameter("mch_id", $settings['mchid']);//商户号
		$wxHongBaoHelper->setParameter("wxappid", $settings['appid']);
		$wxHongBaoHelper->setParameter("send_name", $settings['send_name']);//红包发送者名称
		// 	$wxHongBaoHelper->setParameter("send_name", "冰点会员");//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $openid);//相对于医脉互通的openid
		$wxHongBaoHelper->setParameter("total_amount", $money);//付款金额，单位分
		$wxHongBaoHelper->setParameter("total_num", $totalmember);//红包发放总人数
		$wxHongBaoHelper->setParameter("amt_type", "ALL_RAND");//红包发放内型
		$wxHongBaoHelper->setParameter("wishing", $settings['wishing']);//红包祝福诧
		// 	$wxHongBaoHelper->setParameter("wishing", "恭喜恭喜");//红包祝福诧
		// 	$wxHongBaoHelper->setParameter("act_name", "冰点裂变红包");//活劢名称
		$wxHongBaoHelper->setParameter("act_name", $settings['act_name']);//活劢名称
		// 	$wxHongBaoHelper->setParameter("remark", "恭喜获得裂变红包");//备注信息
		$wxHongBaoHelper->setParameter("remark", $settings['remark']);//备注信息
		$postXml = $wxHongBaoHelper->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendgroupredpack';
		$responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
	
		logging_run(json_encode($responseObj),'','responseObj');
	
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
	
	/*
	 * 经典的概率算法，
	* $proArr是一个预先设置的数组，
	* 假设数组为：array(100,200,300，400)，
	* 开始是从1,1000 这个概率范围内筛选第一个数是否在他的出现概率范围之内，
	* 如果不在，则将概率空间，也就是k的值减去刚刚的那个数字的概率空间，
	* 在本例当中就是减去100，也就是说第二个数是在1，900这个范围内筛选的。
	* 这样 筛选到最终，总会有一个数满足要求。
	* 就相当于去一个箱子里摸东西，
	* 第一个不是，第二个不是，第三个还不是，那最后一个一定是。
	* 这个算法简单，而且效率非常 高，
	* 关键是这个算法已在我们以前的项目中有应用，尤其是大数据量的项目中效率非常棒。
	*/
	function get_rand($proArr) {
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset($proArr);
		return $result;
	}
	

}