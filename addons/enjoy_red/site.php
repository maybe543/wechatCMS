<?php
/**
 * 翻红包模块微站定义
 *
 * @author 乐不思蜀
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
define('MB_ROOT', IA_ROOT . '/addons/enjoy_red');

class Enjoy_redModuleSite extends WeModuleSite {

// 	public function doMobileData() {
// 		//这个操作被定义用来呈现 功能封面
// 	}
// 	public function doWebRule() {
// 		//这个操作被定义用来呈现 规则列表
// 	}
// 	public function doWebBasic() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebRed() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
// 	public function doWebLog() {
// 		//这个操作被定义用来呈现 管理中心导航菜单
// 	}
	public function tpl_form_field_imagepic($name, $value = '', $default = '', $options = array()) {
		global $_W;
	
		if(empty($default)) {
			$default = './resource/images/nopic.jpg';
		}
		$val = $default;
		if(!empty($value)) {
			$val = tomedia($value);
		}
		if(empty($options['tabs'])){
			$options['tabs'] = array('upload'=>'active', 'browser'=>'', 'crawler'=>'');
		}
		if(!empty($options['global'])){
			$options['global'] = true;
		} else {
			$options['global'] = false;
		}
		if(empty($options['class_extra'])) {
			$options['class_extra'] = '';
		}
		if (isset($options['dest_dir']) && !empty($options['dest_dir'])) {
			if (!preg_match('/^\w+([\/]\w+)?$/i', $options['dest_dir'])) {
				exit('图片上传目录错误,只能指定最多两级目录,如: "we7_store","we7_store/d1"');
			}
		}
	
		$options['direct'] = true;
		$options['multi'] = false;
	
		if(isset($options['thumb'])){
			$options['thumb'] = !empty($options['thumb']);
		}
	
		$s = '';
		if (!defined('TPL_INIT_IMAGE')) {
			$s = '
		<script type="text/javascript">
			function showImageDialog(elm, opts, options) {
				require(["util"], function(util){
					var btn = $(elm);
					var ipt = btn.parent().prev();
					var val = ipt.val();
					var img = ipt.parent().next().children();
	
					util.image(val, function(url){
						if(url.url){
							if(img.length > 0){
								img.get(0).src = url.url;
							}
							ipt.val(url.filename);
							ipt.attr("filename",url.filename);
							ipt.attr("url",url.url);
						}
						if(url.media_id){
							if(img.length > 0){
								img.get(0).src = "";
							}
							ipt.val(url.media_id);
						}
					}, opts, options);
				});
			}
			function deleteImage(elm){
				require(["jquery"], function($){
					$(elm).prev().attr("src", "./resource/images/nopic.jpg");
					$(elm).parent().prev().find("input").val("");
				});
			}
		</script>';
			define('TPL_INIT_IMAGE', true);
		}
	
	$s .= "<div class='input-group ". $options['class_extra'] ."'><input type='text' name='".$name."' value='".$value."'".($options["extras"]["text"] ? $options["extras"]["text"] : "")." class='form-control' autocomplete='off'><span class='input-group-btn'><button class='btn btn-default' type='button' onclick='showImageDialog(this, \'" . base64_encode(iserializer($options)) . "\', ". str_replace('"','\'', json_encode($options)).");'>选择图片</button></span></div>";
		if(!empty($options['tabs']['browser']) || !empty($options['tabs']['upload'])){
			$s .="<div class='input-group ". $options['class_extra'] ."' style='margin-top:.5em;'><img src='" . $val . "' onerror='this.src=\'".$default."\'; this.title=\'图片未找到.\'' class='img-responsive img-thumbnail' ".($options['extras']['image'] ? $options['extras']['image'] : '')." width='150' /><em class='close' style='position:absolute; top: 0px; right: -14px;' title='删除这张图片' onclick='deleteImage(this)'>×</em></div>";
		}
		return $s;
	}
	//提现
	public function doMobilecash(){
		global $_W,$_GPC;
		$uniacid=$_W['uniacid'];
		$openid=$_GPC['openid'];
		require_once MB_ROOT . '/controller/Act.class.php';
		$act=new Act();
		$actdetail=$act->getact();
		$puid=intval($_GET['puid']);
		//授权登录，获取粉丝信息
		$user = $this->auth($puid);
		//查询
		$money=pdo_fetchcolumn("select SUM(money) from ".tablename('enjoy_red_log')." where uniacid=".$uniacid." and openid='".$openid."'");
		//先判断是否关注
		if($actdetail['csahgz']==1&&$user['subscribe']==0){
			$res['type']=-4;//未关注
			exit();
		}
		if($money>=1){
			$fee=$money*100;
			//开始兑换
			$api = $this->module['config']['api'];
			if(empty($api)) {
				$res['type']=-2;//系统未开放
				exit();
			}
			
			$url='https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
			$pars = array();
			$pars['mch_appid'] =$api['appid'];
			$pars['mchid']=$api['mchid'];
			$pars['nonce_str'] =random(32);
			$pars['partner_trade_no'] =time().random(3,1);
			$pars['openid'] =$openid;
			$pars['check_name'] ='NO_CHECK' ;
			//$pars['re_user_name'] ='' ;
			$pars['amount'] =$fee;
			$pars['desc'] ='分享好友后获得'.$actdetail['share_chance'].$actdetail['unit'].','.$_W['account']['name'].'('.$_W['account']['account'].')现金小游戏';
			$pars['spbill_create_ip'] =$api['ip'];
			
			ksort($pars, SORT_STRING);
			$string1 = '';
			foreach ($pars as $k => $v) {
				$string1 .= "{$k}={$v}&";
			}
			$string1 .= "key=".$api['password'];
			$pars['sign'] = strtoupper(md5($string1));
			$xml = array2xml($pars);
			$extras = array();
			$extras['CURLOPT_CAINFO'] = MB_ROOT . '/cert/rootca.pem.' . $uniacid;
			$extras['CURLOPT_SSLCERT'] = MB_ROOT . '/cert/apiclient_cert.pem.' . $uniacid;
			$extras['CURLOPT_SSLKEY'] = MB_ROOT . '/cert/apiclient_key.pem.' . $uniacid;
			$procResult = null;
			load()->func('communication');
			$resp = ihttp_request($url, $xml, $extras);
			if (is_error($resp)) {
				$procResult = $resp;
			} else {
				$arr=json_decode(json_encode((array) simplexml_load_string($resp['content'])), true);
				$xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
				$dom = new \DOMDocument();
				if ($dom->loadXML($xml)) {
					$xpath = new \DOMXPath($dom);
					$code = $xpath->evaluate('string(//xml/return_code)');
					$ret = $xpath->evaluate('string(//xml/result_code)');
					if (strtolower($code) == 'success' && strtolower($ret) == 'success') {
						$procResult =  array('errno'=>0,'error'=>'success');;
					} else {
						$error = $xpath->evaluate('string(//xml/err_code_des)');
						$procResult = array('errno'=>-2,'error'=>$error);
					}
				} else {
					$procResult = array('errno'=>-1,'error'=>'未知错误');
				}
			}
			if ($procResult['errno']!=0) {
				//系统太忙，歇口气再玩吧
				$res['type']=-3;
				$res['error']=$procResult['error'];
			}else{
				//红包个数--
			//	pdo_query("update ".tablename('enjoy_red_rule')." set rcount=rcount-1 where uniacid=".$uniacid." and id=".$rid."");
				//计数
					
				$insert=array(
						'uniacid'=>$uniacid,
						'openid'=>$openid,
						'money'=>-$money,
						'createtime'=>TIMESTAMP
				);
				$ress=pdo_insert('enjoy_red_log',$insert);
				$res['type']=1;
				if($ress==1){
					//模板推送
					//添加模板消息
					require_once MB_ROOT . '/controller/weixin.class.php';
					$url=$_W['siteroot'].$this->createMobileUrl("mylog");
					//$config = $this->module['config']['api'];
					//echo $xxquan;
					$template = array(
							'touser'      => $openid,
							'template_id' => $api['mid'],
							'url'         => $url,
							'topcolor'    => '#743a3a',
							'data' 		  => array('first'=>array('value'=>urlencode('恭喜您，提现成功，请实时查看微信到账通知'),'color'=>'#2F1B58'),
									'money'=>array('value'=>urlencode($money.'元'),'color'=>'#2F1B58'),
									'timet'=>array('value'=>urlencode(date('y-m-d h:i:s',time())),'color'=>'#2F1B58'),
									'remark'=>array('value'=>urlencode('分享好友后获得'.$actdetail['share_chance'].$actdetail['unit'].','.$_W['account']['name'].'('.$_W['account']['account'].')现金直接到账游戏,点击查看提现记录哦'),'color'=>'#2F1B58'),
							)
					);
					//$api = $this->module['config']['api'];
					$weixin = new class_weixin($api['appid'],$api['secret']);
					$weixin->send_template_message(urldecode(json_encode($template)));

				}
			
			}
			
		}else{
			//不足一元不能兑换
			$res['type']=-1;
			
		}
		$res['unit']=$actdetail['unit'];
		if($money<0.01){
			$res['money']=0.00;
		}else{
			$res['money']=$money;
		}
		
		echo json_encode($res);
		exit();
		
		
		
	}
	
	protected function auth($puid) {
		global $_W;
		//return array('uid' => '1', 'gender' => '男', 'state' => '山西', 'city' => '太原');
		#debug
		session_start();
		$openid = $_SESSION['__:proxy:openid'];
		require_once MB_ROOT . '/controller/Fans.class.php';
		$f = new Fans();
		if(!empty($openid)) {
			$exists = $f->getOne($openid, true);
			if(!empty($exists)) {
				return $exists;
			}
		}
	
		$api = $this->module['config']['api'];
		if(empty($api)) {
			message('系统还未开放');
		}
		$callback = $_W['siteroot'] . 'app' . substr($this->createMobileUrl('auth',array('puid'=>$puid)), 1);
		$callback = urlencode($callback);
		$state = $_SERVER['REQUEST_URI'];
		$stateKey = substr(md5($state), 0, 8);
		$_SESSION['__:proxy:forward'] = $state;
		$forward = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$api['appid']}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$stateKey}#wechat_redirect";
		header('Location: ' . $forward);
		exit;
	}
	public function doMobileAuth() {
		global $_GPC, $_W;
		$puid=$_GPC['puid'];
		session_start();
		$api = $this->module['config']['api'];
		if(empty($api)) {
			message('系统还未开放');
		}
		$code = $_GPC['code'];
		load()->func('communication');
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$api['appid']}&secret={$api['secret']}&code={$code}&grant_type=authorization_code";
		$resp = ihttp_get($url);
		if(is_error($resp)) {
			message('系统错误, 详情: ' . $resp['message']);
		}
		$auth = @json_decode($resp['content'], true);

		if(is_array($auth) && !empty($auth['openid'])) {
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token={$auth['access_token']}&openid={$auth['openid']}&lang=zh_CN";
			$resp = ihttp_get($url);
			if(is_error($resp)) {
				message('系统错误');
			}
			$info = @json_decode($resp['content'], true);
			//$openid=$info['openid'];
			$account = account_fetch($_W['uniacid']);
			$level=$account['level'];
			//判断公众号类别
			if($level<4){
				if(empty($puid)){
					$subscribe=1;
				}else{
					$subscribe=0;
				}
// 				if($level==3){
// 					$openid=$_W['openid'];
// 				}
			}else{
				$accObj = WeiXinAccount::create($_W['account']);
				$userinfo = $accObj->fansQueryInfo($info['openid']);
				$subscribe=$userinfo['subscribe'];
			}
			if(is_array($info) && !empty($info['openid'])) {
				$user = array();
				$user['uniacid']         = $_W['uniacid'];
				$user['openid']          = $info['openid'];
				$user['unionid']         = $info['unionid'];
				$user['nickname']        = $info['nickname'];
				$user['gender']          = $info['sex'];
				$user['city']            = $info['city'];
				$user['state']           = $info['province'];
				$user['avatar']          = $info['headimgurl'];
				$user['country']         = $info['country'];
				$user['subscribe']         = $subscribe;
				$user['subscribe_time']         = TIMESTAMP;
				$user['puid']         = $puid;
				if(!empty($user['avatar'])) {
					$user['avatar'] = rtrim($user['avatar'], '0');
					$user['avatar'] .= '132';
				}
	
				require_once MB_ROOT . '/controller/Fans.class.php';
				$f = new Fans();

					$f->save($user);

				
	
				$_SESSION['__:proxy:openid'] = $user['openid'];
				$forward = $_SESSION['__:proxy:forward'];
				header('Location: ' . $forward);
				exit();
			}
		}
		message('系统错误');
	}
	//概率计算
	public function getrand($proArr) {
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
		unset ($proArr);
	
		return $result;
	}
	function inject_check($sql_str) {
		return eregi('select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str);
	}
	//防sql注入
	function verify_id($id=null) {
		if(!$id) {
			exit('没有提交参数！');
		} elseif($this->inject_check($id)) {
			exit('提交的参数非法！');
		} elseif(!is_numeric($id)) {
			exit('提交的参数非法！');
		}
		$id = intval($id);
	
		return $id;
	}
	
	
	
	
	
	
	
}