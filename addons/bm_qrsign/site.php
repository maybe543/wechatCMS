<?php
defined('IN_IA') or exit('Access Denied');
class bm_qrsignModuleSite extends WeModuleSite {
    public $weid;
    public function __construct() {
        global $_W;
        $this->weid = IMS_VERSION<0.6?$_W['weid']:$_W['uniacid'];
    }
	
	public function doWebRecord(){
		global $_GPC, $_W;
		checklogin();
		load()->func('tpl');
		$rid = intval($_GPC['id']);
		$condition = '';
		if (!empty($_GPC['username'])) {
			$condition .= " AND username like '%{$_GPC['username']}%' ";
		}
		if (!empty($_GPC['sign_time'])) {
			$condition .= " AND sign_time = '%{$_GPC['username']}%' ";
		}
		if (empty($starttime) || empty($endtime)) {
			$starttime = strtotime('-1 month');
			$endtime = TIMESTAMP;
		}
		if (!empty($_GPC['time'])) {
			$starttime = strtotime($_GPC['time']['start']);
			$endtime = strtotime($_GPC['time']['end']) + 86399;
			$condition .= " AND sign_time >= '{$starttime}' AND sign_time <= '{$endtime}' ";
			//print_r('abc');print_r($condition);exit;
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename('bm_qrsign_record')." WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_record') . " WHERE rid = '$rid' ");
		$pager = pagination($total, $pindex, $psize);
		$memberlist = pdo_fetchall("SELECT distinct fromuser FROM ".tablename('bm_qrsign_record')."  WHERE rid = '$rid' ");
		$membertotal = count($memberlist);
		include $this->template('record');
	}

	public function doWebWinner(){
		global $_GPC, $_W;
		checklogin();
		$rid = intval($_GPC['id']);
		$condition = '';
		if (!empty($_GPC['username'])) {
			$condition .= " AND username like '%{$_GPC['username']}%' ";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename('bm_qrsign_winner')." WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' ");
		$pager = pagination($total, $pindex, $psize);
		$memberlist = pdo_fetchall("SELECT distinct from_user FROM ".tablename('bm_qrsign_winner')."  WHERE rid = '$rid' ");
		$membertotal = count($memberlist);
		include $this->template('winner');
	}
	
	public function doWebPlay(){
		global $_GPC, $_W;
		checklogin();
		$rid = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//$list = pdo_fetchall("SELECT id, content, from_user, type, createtime FROM ".tablename('wxwall_message')." WHERE rid = '{$wall['rid']}' AND isshow = '2' AND from_user <> '' ORDER BY createtime DESC");
		if ($reply['qrtype'] == 0) {
			$list = pdo_fetchall("SELECT id,username as content,fromuser as from_user,'text' as type,sign_time as createtime,username as nickname,avatar FROM ".tablename('bm_qrsign_record')." WHERE rid = '$rid' and play_status=0 ORDER BY id DESC");		
		} else {
			$list = pdo_fetchall("SELECT id,username as content,fromuser as from_user,'text' as type,dateline as createtime,username as nickname,avatar FROM ".tablename('bm_qrsign_payed')." WHERE rid = '$rid' and play_status=0 and status=1 ORDER BY id DESC");		
		}
		//$this->formatMsg($list);
		//print_r('<pre>');print_r($list);exit;
		include $this->template('play');
	}
	
	public function doWebAjaxsubmit() {
		
		global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$result=$reply['play_status'];
		echo $result;
	}

	public function doWebAddwinner() {
		global $_GPC, $_W;
		checklogin();
		$rid = intval($_GPC['id']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//$list = pdo_fetchall("SELECT id, content, from_user, type, createtime FROM ".tablename('wxwall_message')." WHERE rid = '{$wall['rid']}' AND isshow = '2' AND from_user <> '' ORDER BY createtime DESC");
		if ($reply['qrtype'] == 0) {
			$message = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_record')." WHERE id = :id LIMIT 1", array(':id'=>intval($_GPC['mid'])));
		} else {
			$message = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_payed')." WHERE id = :id LIMIT 1", array(':id'=>intval($_GPC['mid'])));
		}
		
		if (empty($message)) {
			message('抱歉，参数不正确！', '', 'error');
		}
		$data = array(
			'rid' => $message['rid'],
			'from_user' => $message['fromuser'],
			'dateline' => TIMESTAMP,
			'username' => $message['username'],
			'avatar' => $message['avatar'],
			'status' => 0,
		);
		pdo_insert('bm_qrsign_winner', $data);
		if ($reply['qrtype'] == 0) {
			pdo_update('bm_qrsign_record', array('play_status' => 1 , 'play_time' => TIMESTAMP) , array('id' => intval($_GPC['mid'])));
		} else {
			pdo_update('bm_qrsign_payed', array('play_status' => 1 , 'play_time' => TIMESTAMP) , array('id' => intval($_GPC['mid'])));
		}
		
			//发送模版消息
			//print_r('<pre>');print_r($payed['status']);
			//if ($payed['status']==0) {
				$url=$reply['urly'];
				$template = array('touser' => $message['fromuser'],
								'template_id' => $reply['templateid'],
								//'url' => $_W['siteroot'].$this->createMobileUrl('index', array()),
								'url' => $url,
								'topcolor' => "#7B68EE",
								'data' => array('first'	=> array('value' => urlencode('恭喜您在'.$_W['account']['name'].'的大屏幕活动中得奖！'),
																 'color' => "#743A3A",
																  ),
											  'keyword1' => array('value' => urlencode($reply['awaremethod']),
																 'color' => "#FF0000",
																  ),
											  'keyword2' 	=> array('value' => urlencode($reply['awaretime']),
																 'color' => "#0000FF",
																  ),
											  'remark' 	=> array('value' => urlencode("感谢您的参与！"),
																 'color' => "#008000",
																  ),
											  )
								);
				//$appid = 'wx15a1f53f11f8c79e';
				//$appsecret = 'd0a28b42361f580e5208181d01430ba6';
				//$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
				//$row = pdo_fetch($sql, array(':acid' => $_W['account']['uniacid']));
				$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
				$row = pdo_fetch($sql, array(':acid' => $_W['account']['uniacid']));
				$appid = $row['key'];
				$appsecret = $row['secret'];
				//print_r('<pre>');print_r($_W);print_r('|+|+|');print_r($appid);print_r('|+|+|');print_r($appsecret);
				$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
				//print_r('|+|+|');print_r($url);print_r('|+|+|');print_r($this->kjSetting['template']);exit;
				$res = $this->http_request($url);
				$result = json_decode($res, true);
				$access_token = $result["access_token"];
				$lasttime = time();
				//print_r('<pre>');print_r($reply);print_r('|+|+|');print_r($template);print_r('|+|+|');print_r($url);print_r('|+|+|');print_r($result);
				$x=$this->send_template_message(urldecode(json_encode($template)),$access_token);
				$result=array(
					'errcode' => $x['errcode'],
					'errmsg' => $x['errmsg'],
					'msgid' => $x['msgid'],
				);
				//message($result, '', 'success');								
				//print_r('<pre>');
				//print_r('|+11+|');print_r($x);
		
		message('', '', 'success');
	}	

    //https请求（支持GET和POST）
    private function http_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }		

    //发送模版消息
	private function send_template_message($data,$access_token)
    {
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $res = $this->http_request($url, $data);
        return json_decode($res, true);
	}	
	
	public function doWebGetaware() {
		global $_GPC, $_W;
		checklogin();
		$rid = intval($_GPC['rid']);
		$condition = '';
		if (!empty($_GPC['username'])) {
			$condition .= " AND username like '%{$_GPC['username']}%' ";
		}
		//print_r($_GPC['status']);
		if (!empty($_GPC['id'])) {
			$id = intval($_GPC['id']);
			//print_r($_GPC['id']);
			pdo_update('bm_qrsign_winner', array('status' => intval($_GPC['status'])), array('id' => $id));
			//message('操作成功！', $this->createWebUrl('awardlist', array('do' => 'awardlist', 'name' => 'bm_floor', 'id' => $id, 'page' => $_GPC['page'], 'state' => '')));
		}		
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename('bm_qrsign_winner')." WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_winner') . " WHERE rid = '$rid' ");
		$pager = pagination($total, $pindex, $psize);
		$memberlist = pdo_fetchall("SELECT distinct from_user FROM ".tablename('bm_qrsign_winner')."  WHERE rid = '$rid' ");
		$membertotal = count($memberlist);
		include $this->template('winner');	
	}
	
	public function doWebPayed(){
		global $_GPC, $_W;
		checklogin();
		$rid = intval($_GPC['id']);
		$condition = '';
		if (!empty($_GPC['username'])) {
			$condition .= " AND username like '%{$_GPC['username']}%' ";
		}
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$list = pdo_fetchall("SELECT * FROM ".tablename('bm_qrsign_payed')." WHERE rid = '$rid' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' ");
		$totalsuccess = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('bm_qrsign_payed') . " WHERE rid = '$rid' and status=1");
		$pager = pagination($total, $pindex, $psize);
		//$memberlist = pdo_fetchall("SELECT distinct from_user FROM ".tablename('bm_qrsign_payed')."  WHERE rid = '$rid' ");
		//$membertotal = count($memberlist);
		include $this->template('payed');
	}	
	
	public function doMobileSign() {
		global $_W, $_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
			message('非法访问，请通过微信打开！');
			die();
        }		
		$rid = trim($_GPC['rid']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//print_r('|+x+|');print_r($rid);
		//print_r('<pre>');print_r('|+x+|');print_r($reply);exit;
		//print_r('abc');
		//print_r($rid);exit;
		if (time() > strtotime($reply['endtime'])) {
			if (empty($reply['memo2'])) {
				$msg='对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
			} else {
				$msg=$reply['memo2'];
			}
			message($msg,$reply['url2'],'success');
		}
		if (time() < strtotime($reply['starttime'])) {
			if (empty($reply['memo1'])) {
				$msg='对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
			} else {
				$msg=$reply['memo1'];
			}
			message($msg,$reply['url1'],'success');
		}
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		//print_r('<pre>');print_r($_W['fans']);exit;
		//if (!empty($_W['fans']['follow'])){ echo '已关注'; } else { echo '未关注'; }exit;
		//print_r('|+x+|');print_r($_W['fans']['follow']);print_r('|+x+|');
		//print_r($reply['pictype']);print_r('|+x+|');
		if ($reply['pictype'] == 1) {
			if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)){
				//print_r($reply['urlx']);
				header("Location: " . $reply['urlx']); 
				//确保重定向后，后续代码不会被执行 
				exit;
			}
		}
		$from_user = $_W['fans']['openid'];
		$rec = pdo_fetch("select * from " . tablename('bm_qrsign_record') . " where rid= " . $rid . " and fromuser= '{$from_user}' order by sign_time desc");
		//print_r('<pre>');print_r('|+x+|');print_r($rec);exit;
		if(!empty($rec)){
			$Date_1=date("Y-m-d",time());
			$Date_2=date("Y-m-d",$rec['sign_time']);
			$Date_List_a1=explode("-",$Date_1);
			$Date_List_a2=explode("-",$Date_2);
			$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
			$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
			$Days=round(($d1-$d2)/3600/24);
			if ($Days == 0) {
				$msg='感谢您的参与，每个人每天只可以签到一次哦！！！';
				message($msg,$reply['urly'],'success');
			}
		}
		
		$insert = array(
			'rid' => $rid,
			'fromuser' => $from_user,
			'username' => $_W['fans']['nickname'],
			'avatar' => $_W['fans']['tag']['avatar'],
			'sign_time' => $_W['timestamp'],
			'credit' => $reply['n'],
		);
		//print_r('<pre>');print_r($insert);exit;
		pdo_insert('bm_qrsign_record', $insert);
		
		$user = fans_search($from_user);
		//print_r('<pre>');print_r('|+x+|');print_r($user);
		//return $this->respText($user['credit1']);
		$sql_member = "SELECT a.uid FROM " . tablename('mc_mapping_fans') . " a inner join " . tablename('mc_members') . " b on a.uid=b.uid WHERE a.openid='{$from_user}'";
		//return $this->respText($sql_member);
		$uid = pdo_fetchcolumn($sql_member);
		//$credit1 = intval($user['credit1']) + intval($reply['n']);
		//print_r('|+x+|');print_r($credit1);
		mc_credit_update($uid , 'credit1' , intval($reply['n']) , array( 0 => 'system', 1 => '扫码签到送积分' ));
		$user = fans_search($from_user);
		//print_r('|+x+|');print_r($reply);
		//print_r('|+x+|');print_r($user);exit;
		//include $this->template('sign');
		//$msg='恭喜签到成功，您已获得奖励积分'.$reply['n'].'分，您目前的总积分为'.$user['credit1'].'分！';
		//message($msg,$reply['urly'],'success');
		message($reply['memo'],$reply['urly'],'success');
	}
	
	public function doMobilePay() {
		global $_W,$_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
			message('非法访问，请通过微信打开！');
			die();
        }		
		$rid = trim($_GPC['rid']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//print_r('<pre>');print_r($reply);exit;
		if (time() > strtotime($reply['endtime'])) {
			if (empty($reply['memo2'])) {
				$msg='对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
			} else {
				$msg=$reply['memo2'];
			}
			message($msg,$reply['url2'],'success');
		}
		if (time() < strtotime($reply['starttime'])) {
			if (empty($reply['memo1'])) {
				$msg='对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
			} else {
				$msg=$reply['memo1'];
			}
			message($msg,$reply['url1'],'success');
		}		
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		//print_r('<pre>');print_r($_W['fans']);exit;
		//if (!empty($_W['fans']['follow'])){ echo '已关注'; } else { echo '未关注'; }exit;
		//print_r('|+x+|');print_r($_W['fans']['follow']);print_r('|+x+|');
		//print_r($reply['pictype']);print_r('|+x+|');
		if ($reply['pictype'] == 1) {
			if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)){
				//print_r($reply['urlx']);
				header("Location: " . $reply['urlx']); 
				//确保重定向后，后续代码不会被执行 
				exit;
			}
		}
		$op = trim($_GPC['op']);
		$qrmoney = $_GPC['qrmoney'];
		$from_user = $_W['fans']['openid'];
		$qrtype = $reply['qrtype'];
		if ($op == 'post') {
			//print_r('<pre>');print_r($reply);exit;
			if ($qrmoney < 0.01) {
				message('支付金额错误，请重新录入！',$this->createMobileUrl('show',array('rid' => $rid,'from_user' => $from_user)),'error');
			}
			$data = array(
				'rid' => $rid,
				'dateline' => TIMESTAMP,
				'clientOrderId' => TIMESTAMP,
				'qrmoney' => $qrmoney,
				'status' => 0,
				'fromuser' => $from_user,
				'username' => $_W['fans']['nickname'],
				'avatar' => $_W['fans']['tag']['avatar'],
				'credit' => $reply['n'],
			);
			//print_r('<pre>');print_r($insert);exit;
			pdo_insert('bm_qrsign_payed', $data);
			
			//构造支付请求中的参数
			$params = array(
				'tid' => $data['clientOrderId'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
				'ordersn' => $data['clientOrderId'],  //收银台中显示的订单号
				'title' => '扫码支付',          //收银台中显示的标题
				'fee' => $data['qrmoney'],      //收银台中显示需要支付的金额,只能大于 0
				'user' => $from_user,     //付款用户, 付款的用户名(选填项)
			);
			//print_r('<pre>');print_r($params);exit;
			//调用pay方法
			$this->pay($params);
			exit;
		}		
		if($reply['qrmoney'] <= 0) {
			message('支付错误, 金额小于0');
		}
		if (!empty($reply['logo'])){
			$qrpicurl = $_W['attachurl'] . $reply['logo'];
		} else {
			$qrpicurl = $_W['attachurl'] . $reply['qrcode'];
		}		
		if($reply['qrinput'] == 1) {
			include $this->template('show');
		} else {
			message('正在提交订单，请稍候',$this->createmobileurl('pay_exec',array('rid' => $rid,'from_user' => $from_user)),'success');
		}
	}

	public function doMobilePay_exec() {
		global $_W,$_GPC;
		$rid = trim($_GPC['rid']);
		$from_user = trim($_GPC['from_user']);
		$reply = pdo_fetch("SELECT * FROM ".tablename('bm_qrsign_reply')." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		//print_r('<pre>');print_r($reply);exit;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
			message('非法访问，请通过微信打开！');
			die();
        }
		$data = array(
			'rid' => $rid,
			'dateline' => TIMESTAMP,
			'clientOrderId' => TIMESTAMP,
			'qrmoney' => $reply['qrmoney'],
			'status' => 0,
			'fromuser' => $from_user,
			'username' => $_W['fans']['nickname'],
			'avatar' => $_W['fans']['tag']['avatar'],
			'credit' => $reply['n'],
		);
		//print_r('<pre>');print_r($insert);exit;
		pdo_insert('bm_qrsign_payed', $data);
		
		//构造支付请求中的参数
		$params = array(
			'tid' => $data['clientOrderId'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
			'ordersn' => $data['clientOrderId'],  //收银台中显示的订单号
			'title' => '扫码支付',          //收银台中显示的标题
			'fee' => $data['qrmoney'],      //收银台中显示需要支付的金额,只能大于 0
			'user' => $from_user,     //付款用户, 付款的用户名(选填项)
		);
		//print_r('<pre>');print_r($params);exit;
		//调用pay方法
		$this->pay($params);
	}

	public function payResult($params) {
		global $_W,$_GPC;
		//一些业务代码
		//根据参数params中的result来判断支付是否成功
		//print_r('<pre>');print_r($params);
		$payed = pdo_fetch("select * from " . tablename('bm_qrsign_payed') . " where clientOrderId = '{$params['tid']}'");
		//print_r('<pre>');print_r($payed);
		$reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $payed['rid']));
		//print_r('<pre>');print_r($reply);exit;
		if ($params['result'] == 'success' && $params['from'] == 'notify') {
			//此处会处理一些支付成功的业务代码
			//print_r('<pre>');print_r($payed);exit;
				$url=$reply['urly'];

				//print_r('<pre>');print_r('|+1+|');print_r($x);exit;
			pdo_update('bm_qrsign_payed',array("status" => 1 , "paytime" => TIMESTAMP),array("clientOrderId" => $params['tid']));
			//print_r($info[resultCode]);exit;
		}
		//因为支付完成通知有两种方式 notify，return,notify为后台通知,return为前台通知，需要给用户展示提示信息
		//return做为通知是不稳定的，用户很可能直接关闭页面，所以状态变更以notify为准
		//如果消息是用户直接返回（非通知），则提示一个付款成功
		if ($params['from'] == 'return') {
			if ($params['result'] == 'success') {
				//此处会处理一些支付成功的业务代码
				//print_r('<pre>');print_r($payed);exit;
					$url=$reply['urly'];
					$template = array('touser' => $reply['openid'],
									'template_id' => $reply['templateid1'],
									//'url' => $_W['siteroot'].$this->createMobileUrl('index', array()),
									'url' => $url,
									'topcolor' => "#7B68EE",
									'data' => array('first'	=> array('value' => urlencode($_W['account']['name'].'有客户完成扫码支付！'),
																	 'color' => "#743A3A",
																	  ),
												  'keyword1' => array('value' => urlencode($payed['clientOrderId']),
																	 'color' => "#FF0000",
																	  ),
												  'keyword2' 	=> array('value' => urlencode(date('Y-m-d H:i:s',time())),
																	 'color' => "#0000FF",
																	  ),
												  'keyword3' 	=> array('value' => urlencode($payed['qrmoney']),
																	 'color' => "#0000FF",
																	  ),															  
												  'remark' 	=> array('value' => urlencode("客户：".$payed['username']),
																	 'color' => "#008000",
																	  ),
												  )
									);				
					//print_r('<pre>');print_r($reply['templateid']);
					$sql = 'SELECT `key`,`secret` FROM ' . tablename('account_wechats') . ' WHERE `acid`=:acid';
					$row = pdo_fetch($sql, array(':acid' => $_W['account']['uniacid']));
					$appid = $row['key'];
					$appsecret = $row['secret'];
					//print_r('<pre>');print_r($_W);print_r('|+|+|');print_r($appid);print_r('|+|+|');print_r($appsecret);
					$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
					//print_r('|+|+|');print_r($url);print_r('|+|+|');print_r($this->kjSetting['template']);exit;
					
					$res = $this->http_request($url);
					$result = json_decode($res, true);
					$access_token = $result["access_token"];
					$lasttime = time();

					$x=$this->send_template_message(urldecode(json_encode($template)),$access_token);
					//print_r('<pre>');print_r('|+x+|');print_r($x);exit;
					message($reply['memo'],$reply['urly'],'success');
					
				//pdo_update('bm_qrsign_payed',array("status" => 1 , "paytime" => TIMESTAMP),array("clientOrderId" => $params['tid']));
				//print_r($info[resultCode]);exit;
			} else {
				message($reply['qrerrormemo'],$reply['qrerrorurl'],'success');
			}
		}
		
		//$msg='恭喜签到成功，您已获得奖励积分'.$reply['n'].'分，您目前的总积分为'.$user['credit1'].'分！';
		//message($reply['memo'],$reply['urly'],'success');
	}

	public function doMobileShow() {
		global $_W,$_GPC;
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && strpos($useragent, 'Windows Phone') === false) {
			message('非法访问，请通过微信打开！');
			die();
        }		
		$rid = trim($_GPC['rid']);
		$reply = pdo_fetch("SELECT * FROM " . tablename('bm_qrsign_reply') . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		if (time() > strtotime($reply['endtime'])) {
			if (empty($reply['memo2'])) {
				$msg='对不起，活动已经于' . $reply['endtime'] . '结束，感谢您的参与！！！';
			} else {
				$msg=$reply['memo2'];
			}
			message($msg,$reply['url2'],'success');
		}
		if (time() < strtotime($reply['starttime'])) {
			if (empty($reply['memo1'])) {
				$msg='对不起，活动将于' . $reply['starttime'] . '开始，敬请期待！！！';
			} else {
				$msg=$reply['memo1'];
			}
			message($msg,$reply['url1'],'success');
		}		
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		if ($reply['pictype'] == 1) {
			if ((empty($_W['fans']['follow'])) || ($_W['fans']['follow'] == 0)){
				//print_r($reply['urlx']);
				header("Location: " . $reply['urlx']); 
				//确保重定向后，后续代码不会被执行 
				exit;
			}
		}		
		
		$op = trim($_GPC['op']);
		$qrmoney = $_GPC['qrmoney'];
		$from_user = $_W['fans']['openid'];
		
		//print_r($rid);print_r('|+|+|');print_r($reply);exit;
		$qrpicurl = $_W['attachurl'] . $reply['qrcode'];
		if ($op == 'post') {
			//print_r('<pre>');print_r($reply);exit;
			if ($qrmoney < 0.01) {
				message('支付金额错误，请重新录入！',$this->createMobileUrl('show',array('rid' => $rid,'from_user' => $from_user)),'error');
			}
			$data = array(
				'rid' => $rid,
				'dateline' => TIMESTAMP,
				'clientOrderId' => TIMESTAMP,
				'qrmoney' => $qrmoney,
				'status' => 0,
				'fromuser' => $from_user,
				'username' => $_W['fans']['nickname'],
				'avatar' => $_W['fans']['tag']['avatar'],
				'credit' => $reply['n'],
			);
			//print_r('<pre>');print_r($insert);exit;
			pdo_insert('bm_qrsign_payed', $data);
			
			//构造支付请求中的参数
			$params = array(
				'tid' => $data['clientOrderId'],      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
				'ordersn' => $data['clientOrderId'],  //收银台中显示的订单号
				'title' => '扫码支付',          //收银台中显示的标题
				'fee' => $data['qrmoney'],      //收银台中显示需要支付的金额,只能大于 0
				'user' => $from_user,     //付款用户, 付款的用户名(选填项)
			);
			//print_r('<pre>');print_r($params);exit;
			//调用pay方法
			$this->pay($params);
			exit;
		} else {
			if ($op == 'sign') {
				$rec = pdo_fetch("select * from " . tablename('bm_qrsign_record') . " where rid= " . $rid . " and fromuser= '{$from_user}' order by sign_time desc");
				//print_r('<pre>');print_r('|+x+|');print_r($rec);exit;
				if(!empty($rec)){
					$Date_1=date("Y-m-d",time());
					$Date_2=date("Y-m-d",$rec['sign_time']);
					$Date_List_a1=explode("-",$Date_1);
					$Date_List_a2=explode("-",$Date_2);
					$d1=mktime(0,0,0,$Date_List_a1[1],$Date_List_a1[2],$Date_List_a1[0]);
					$d2=mktime(0,0,0,$Date_List_a2[1],$Date_List_a2[2],$Date_List_a2[0]);
					$Days=round(($d1-$d2)/3600/24);
					if ($Days == 0) {
						$msg='感谢您的参与，每个人每天只可以签到一次哦！！！';
						message($msg,$reply['urly'],'success');
					}
				}
				
				$insert = array(
					'rid' => $rid,
					'fromuser' => $from_user,
					'username' => $_W['fans']['nickname'],
					'avatar' => $_W['fans']['tag']['avatar'],
					'sign_time' => $_W['timestamp'],
					'credit' => $reply['n'],
				);
				//print_r('<pre>');print_r($insert);exit;
				pdo_insert('bm_qrsign_record', $insert);
				
				$user = fans_search($from_user);
				//print_r('<pre>');print_r('|+x+|');print_r($user);
				//return $this->respText($user['credit1']);
				$sql_member = "SELECT a.uid FROM " . tablename('mc_mapping_fans') . " a inner join " . tablename('mc_members') . " b on a.uid=b.uid WHERE a.openid='{$from_user}'";
				//return $this->respText($sql_member);
				$uid = pdo_fetchcolumn($sql_member);
				//$credit1 = intval($user['credit1']) + intval($reply['n']);
				//print_r('|+x+|');print_r($credit1);
				mc_credit_update($uid , 'credit1' , intval($reply['n']) , array( 0 => 'system', 1 => '扫码签到送积分' ));
				$user = fans_search($from_user);
				//print_r('|+x+|');print_r($reply);
				//print_r('|+x+|');print_r($user);exit;
				//include $this->template('sign');
				//$msg='恭喜签到成功，您已获得奖励积分'.$reply['n'].'分，您目前的总积分为'.$user['credit1'].'分！';
				//message($msg,$reply['urly'],'success');
				message($reply['memo'],$reply['urly'],'success');
				
			}
		}
		include $this->template('show');
	}
}
?>