<?php
/**
 * 模块定义：规则内容
 *
 * @author 石头鱼
 * @url http://www.00393.com/
 */
defined('IN_IA') or exit('Access Denied');

class Stonefish_fightingModuleSite extends WeModuleSite {	

	//微信访问限制
	function Weixin(){
		global $_W;
		$setting = $this->module['config'];
		if($setting['stonefish_fighting_jssdk']==2 && !empty($setting['jssdk_appid']) && !empty($setting['jssdk_secret'])){
			$_W['account']['jssdkconfig'] = $this->getSignPackage($setting['jssdk_appid'],$setting['jssdk_secret']);
		}
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') === false){
			if($setting['weixinvisit']==1){
				include $this->template('remindnotweixin');
			    exit;
			}else{
				return true;
			}
		}else{
			return true;
		}
    }
	//微信访问限制
	//json返回参数
	public function Json_encode($_data) {
        die(json_encode($_data));
		exit;
    }
	//json返回参数
	//发送消息模板
	public function Seed_tmplmsg($openid,$tmplmsgid,$rid,$params) {
        global $_GPC,$_W;
		$reply = pdo_fetch("select title,starttime,endtime FROM ".tablename("stonefish_fighting_reply")." where rid = :rid", array(':rid' => $rid));		
		$listtotal = pdo_fetchcolumn("select xuninum+fansnum as total from ".tablename("stonefish_fighting_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$tmplmsg = pdo_fetch("select * FROM ".tablename("stonefish_fighting_tmplmsg")." where id = :id", array(':id' => $tmplmsgid));
		$fans = pdo_fetch("select * FROM ".tablename("stonefish_fighting_fans")." where rid = :rid and from_user = :from_user", array(':rid' => $rid, ':from_user' => $openid));
		$fans['realname'] = empty($fans['realname']) ? $fans['nickname'] : $fans['realname'];
		if(!empty($tmplmsg)){
			if($params['do']=='index'){
				$appUrl= $this->createMobileUrl('entry', array('rid' => $rid,'entrytype' => 'index'),true);
			}else{
				$appUrl= $this->createMobileUrl($params['do'], array('rid' => $rid),true);
			}
		    $appUrl=$_W['siteroot'].'app/'.substr($appUrl,2);
			$str = array('#活动名称#'=>$reply['title'],'#参与人数#'=>$listtotal,'#活动时间#'=>date('Y-m-d H:i', $reply['starttime']).'至'.date('Y-m-d H:i', $reply['endtime']),'#粉丝昵称#'=>$fans['nickname'],'#真实姓名#'=>$fans['realname'],'#现在时间#'=>date('Y-m-d H:i', time()),'#今日排名#'=>$params['daypaihang'],'#总排名#'=>$params['paihang']);
			$datas['first'] = array('value'=>strtr($tmplmsg['first'],$str),'color'=>$tmplmsg['firstcolor']);
			for($i = 1; $i <= 10; $i++) {
				if(!empty($tmplmsg['keyword'.$i]) && !empty($tmplmsg['keyword'.$i.'code'])){
					$datas[$tmplmsg['keyword'.$i.'code']] = array('value'=>strtr($tmplmsg['keyword'.$i],$str),'color'=>$tmplmsg['keyword'.$i.'color']);
				}
			}
			$datas['remark'] = array('value'=>strtr($tmplmsg['remark'],$str),'color'=>$tmplmsg['remarkcolor']);
	        $data=json_encode($datas);
			
			load()->func('communication');
            load()->classs('weixin.account');
            $accObj = WeixinAccount::create($_W['acid']);
            $access_token = $accObj->fetch_token();
			if (empty($access_token)) {
                return;
            }
			$postarr = '{"touser":"'.$openid.'","template_id":"'.$tmplmsg['template_id'].'","url":"'.$appUrl.'","topcolor":"'.$tmplmsg['topcolor'].'","data":'.$data.'}';
            $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token, $postarr);
			//添加消息发送记录
			$tmplmsgdata = array(
				'rid' => $rid,
				'uniacid' => $_W['uniacid'],
				'from_user' => $openid,
				'tmplmsgid' => $tmplmsgid,
				'tmplmsg' => $postarr,
				'createtime' => TIMESTAMP,
			);
			pdo_insert('stonefish_fighting_fanstmplmsg', $tmplmsgdata);
			//添加消息发送记录
			return true;
		}
		return;
    }
	//发送消息模板
	//随机抽奖ID
	function Get_rand($proArr) {   
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
	//随机抽奖ID
	//虚拟人数据配置
	function Xuni_time($reply){
	    $now = time();
		if($now-$reply['xuninum_time']>$reply['xuninumtime']){
		    pdo_update('stonefish_fighting_reply', array('xuninum_time' => $now,'xuninum' => $reply['xuninum']+mt_rand($reply['xuninuminitial'],$reply['xuninumending'])), array('id' => $reply['id']));
		}
	}
	//虚拟人数据配置
	//分享设置
	function Get_share($rid,$from_user,$title) {
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		if (!empty($rid)) {
			$listtotal = pdo_fetchcolumn("select xuninum+fansnum as total from ".tablename("stonefish_fighting_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
        }
		if (!empty($from_user)) {
		    $fans = pdo_fetch("select realname,nickname FROM ".tablename("stonefish_fighting_fans")." where uniacid= :uniacid AND rid= :rid AND from_user= :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
		}
		$str = array('#参与人数#'=>$listtotal,'#粉丝昵称#'=>$fans['nickname'],'#真实姓名#'=>$fans['realname']);
		$result = strtr($title,$str);
        return $result;
    }
	//分享设置	
	//提示出错页
	function Message_tips($rid,$msg,$url){
        global $_W;
		$reply = pdo_fetch("select msgadpictime,msgadpic from ".tablename("stonefish_fighting_reply")." where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$time = $reply['msgadpictime'];
		$msgadpic = iunserializer($reply['msgadpic']);
		$msgadpicid = array_rand($msgadpic);
		$msgadpic =$msgadpic[$msgadpicid];
		if(empty($msg)){
			$msg = '未知错误！';
		}
		include $this->template('message');
		exit;
    }
	//提示出错页
	//获取openid
	function Get_openid() {
        global $_W;
		$from_user = array();
		$from_user['openidtrue'] = $_SESSION['openid'];
		$from_user['openid'] = $_W['openid'];
		$setting = $this->module['config'];
		if($_W['account']['level']<4 && $setting['stonefish_fighting_oauth']==1){
			$from_user['openid'] = $_SESSION['oauth_openid'];
		}
		if($_W['account']['level']<4 && $setting['stonefish_fighting_oauth']==2){
			$from_user['openid'] = $_COOKIE["stonefish_oauth_from_user"];
		}
		if(empty($from_user['openid'])){
			if (isset($_COOKIE["user_oauth2_wuopenid"])){
				$from_user['openid'] = $_COOKIE["user_oauth2_wuopenid"];
			}
		}
		//重新判断是否关注用户
		if($_W['account']['level']==4){
		    $fans_member = pdo_fetch("select follow,uid from " . tablename('mc_mapping_fans') . " where uniacid = :uniacid and acid = :acid and openid = :openid order by `fanid` desc", array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid'], ':openid' => $from_user['openid']));
		    if(!empty($fans_member)){
			    $_W['fans']['follow'] = $fans_member['follow'];
		        $_W['member']['uid'] = $fans_member['uid'];
		    }
		}elseif(!empty($_SESSION['openid'])){
			$fans_member = pdo_fetch("select follow,uid from " . tablename('mc_mapping_fans') . " where uniacid = :uniacid and acid = :acid and openid = :openid order by `fanid` desc", array(':uniacid' => $_W['uniacid'], ':acid' => $_W['acid'], ':openid' => $_SESSION['openid']));
		    if(!empty($fans_member)){
			    $_W['fans']['follow'] = $fans_member['follow'];
		        $_W['member']['uid'] = $fans_member['uid'];
		    }
		}
		//重新判断是否关注用户
		return $from_user;
    }
	//获取openid
	//获取粉丝数据
	function Get_UserInfo($power,$rid,$iid,$page_fromuser,$entrytype) {   
        global $_W;
		$setting = $this->module['config'];
		if(!empty($_COOKIE['stonefish_userinfo'])){
			$userinfo = iunserializer($_COOKIE["stonefish_userinfo"]);
			if($_COOKIE["stonefish_userinfo_power"]!=$power || empty($userinfo['openid'])){
				setcookie("stonefish_userinfo", '', time()-7200);
				setcookie("stonefish_userinfo_power", '', time()-7200);
				$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
				$appUrl=substr($appUrl,2);
				$url = $_W['siteroot'] ."app/".$appUrl;
				header("location: $url");
				exit;
			}
			if(empty($userinfo['nickname']) && $power==2){
				setcookie("stonefish_userinfo", '', time()-7200);
				setcookie("stonefish_userinfo_power", '', time()-7200);
				$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
				$appUrl=substr($appUrl,2);
				$url = $_W['siteroot'] ."app/".$appUrl;
				header("location: $url");
				exit;
			}
			if(empty($userinfo['headimgurl']) && $power==2){
				$userinfo['headimgurl'] = MODULE_URL.'template/images/avatar.jpg';
			}
		}elseif($setting['stonefish_fighting_oauth']>=1 || $_W['account']['level']==4){
			$appUrl=$this->createMobileUrl('entry', array('rid' => $rid,'iid' => $iid,'from_user' => $page_fromuser,'entrytype' => $entrytype),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
			header("location: $url");
			exit;
		}else{
			$userinfo = array('headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名');
		}
		return $userinfo;
	}
	//获取粉丝数据
	//活动状态
	function Check_reply($reply) {   
		if ($reply == false) {
            $this->message_tips($reply['rid'],'抱歉，活动不存在，您穿越了！');
        }else{
			if ($reply['isshow'] == 0) {
				$this->message_tips($reply['rid'],'抱歉，活动暂停，请稍后...');
			}
			if ($reply['starttime'] > time()) {
				$this->message_tips($reply['rid'],'抱歉，活动未开始，请于'.date("Y-m-d H:i:s", $row['starttime']) .'参加活动!');
			}			
		}
		return true;
    }
	//活动状态
	//获取关健词
	function Rule_keyword($rid) {   
		$keyword = pdo_fetchall("select content from ".tablename('rule_keyword')." where rid=:rid and type=1",array(":rid"=>$rid));
        foreach ($keyword as $keywords){
			$rule_keyword .= $keywords['content'].',';
		}
		$rule_keyword = substr($rule_keyword,0,strlen($rule_keyword)-1);
		return $rule_keyword;
    }
	//获取关健词
	//认证第二部获取 openid和accessToken
    public function doMobileauth2(){
        global $_W, $_GPC;
		$setting = $this->module['config'];
        $entrytype = $_GPC['entrytype'];
        $code = $_GPC['code'];                
        $rid = $_GPC['rid'];
		$tokenInfo = $this->getAuthTokenInfo($code,$_GPC['power']);
        $from_user = $tokenInfo['openid'];
		setcookie("stonefish_userinfo", iserializer($tokenInfo), time()+3600*24*$setting['stonefish_oauth_time']);
		setcookie("stonefish_userinfo_power", $_GPC['power'], time()+3600*24*$setting['stonefish_oauth_time']);
		setcookie("stonefish_oauth_from_user", $from_user, time()+3600*24*$setting['stonefish_oauth_time']);
        if ($entrytype == "index") { // 粉丝参与活动
		    $appUrl= $this->createMobileUrl('index', array('rid' => $rid),true);
		    $appUrl=substr($appUrl,2);
            $url = $_W['siteroot'] . "app/".$appUrl;
        } elseif ($entrytype == "shareview") { // 好友进入认证
            $appUrl=$this->createMobileUrl('shareview', array('rid' => $rid,"fromuser" => $_GPC['from_user']),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
        }
        header("location: $url");
		exit;
    }
	//认证第二部获取 openid和accessToken
    //获取token信息
    public function getAuthTokenInfo($code,$power){
        global $_GPC, $_W;
		if ($_W['account']['level']==4){
			$appid = $_W['account']['key'];
            $secret = $_W['account']['secret'];
		}else{
			$setting = $this->module['config'];
			if($setting['stonefish_fighting_oauth']==1 && !empty($_W['oauth_account']['key']) && !empty($_W['oauth_account']['secret'])){
				$appid = $_W['oauth_account']['key'];
                $secret = $_W['oauth_account']['secret'];
			}
			if($setting['stonefish_fighting_oauth']==2 && !empty($setting['appid']) && !empty($setting['secret'])){
				$appid = $setting['appid'];
                $secret = $setting['secret'];
			}
		}
        load()->func('communication');
        $oauth2_code = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code";
        $content = ihttp_get($oauth2_code);
        $token = @json_decode($content['content'], true);
        if (empty($token) || ! is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
            echo '<h1>获取微信公众号授权' . $code . '失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />' . $content['meta'] . '<h1>';
            exit();
        }else{
			if($power==1){
				$token = array('openid'=>$token['openid'],'headimgurl' => MODULE_URL.'template/images/avatar.jpg','nickname' => '匿名');
			}else{
				$token = $this->getUserInfo($token['openid'], $token['access_token']);
			}
		}
        return $token;
    }
	//获取token信息
    //获取用户信息
    public function getUserInfo($openid, $access_token)    {
		load()->func('communication');
        $tokenUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
        $content = ihttp_get($tokenUrl);
        $userInfo = @json_decode($content['content'], true);
        return $userInfo;
    }
	//获取用户信息
	//微站导航
	public function Gethomeurl(){
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$time = time();
		$urls = array();
		$list = pdo_fetchall("select rid, title FROM ".tablename('stonefish_fighting_reply')." where uniacid = :uniacid and starttime <= :time and endtime >= :time and isshow=1", array('uniacid' => $uniacid,'time' => $time));
		if(!empty($list)){
			foreach($list as $row){
				$urls[] = array('title'=>$row['title'], 'url'=> $_W['siteroot']."app".substr($this->createMobileUrl('index', array('rid' => $row['rid'])),true),2);
			}
		}
		return $urls;
	}    
	//微站导航
	//入口列表
	public function doMobileListentry() {
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$time = time();
		$from_user = $_W['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		
		$cover_reply = pdo_fetch("select * FROM ".tablename("cover_reply")." where uniacid = :uniacid and module = :module", array(':uniacid' => $uniacid, ':module' => 'stonefish_fighting'));
		//活动列表
		$reply = pdo_fetchall("select * FROM ".tablename("stonefish_fighting_reply")." where uniacid = :uniacid and isshow = 1 and starttime <= :time  and endtime >= :time ORDER BY `endtime` DESC", array(':uniacid' => $uniacid, ':time' => $time));
		foreach ($reply as $mid => $replys) {
			$reply[$mid]['num'] = pdo_fetchcolumn("select COUNT(id) FROM ".tablename("stonefish_fighting_fans")." where uniacid = :uniacid and rid = :rid and status=1", array(':uniacid' => $uniacid, ':rid' => $replys['rid']));
			$reply[$mid]['is'] = pdo_fetchcolumn("select COUNT(id) FROM ".tablename("stonefish_fighting_fans")." where uniacid = :uniacid and rid = :rid and from_user = :from_user and status=1", array(':uniacid' => $uniacid, ':rid' => $replys['rid'], ':from_user' => $from_user));
			$reply[$mid]['start_picurl'] = toimage($replys['start_picurl']);
		}
		//活动列表
		//查询参与情况
		$usernum = pdo_fetchcolumn("select COUNT(id) FROM ".tablename("stonefish_fighting_fans")." where uniacid = :uniacid and from_user = :from_user and status=1", array(':uniacid' => $uniacid, ':from_user' => $from_user));
		//查询参与情况
		if($this->Weixin()){
			include $this->template('listentry');
		}else{
			$this->Weixin();
		}
	}
	//入口列表
	//会员中心
	public function doMobileMyprofile() {
		global $_GPC,$_W;
		$uniacid = $_W['uniacid'];
		$time = time();
		$from_user = $_W['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));

		echo "会员中心显示内容";
		exit;

		if($this->Weixin()){
			include $this->template('myprofile');
		}else{
			$this->Weixin();
		}		
	}
	//会员中心
	//进入页
	public function doMobileEntry() {
		global $_GPC, $_W;
		$this->Weixin();
		$rid = intval($_GPC['rid']);
		$iid = intval($_GPC['iid']);
		$entrytype = $_GPC['entrytype'];
		$uniacid = $_W['uniacid'];       
		$acid = $_W['acid'];
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));		
        //活动状态
		$this->check_reply($reply);		
		//活动状态		
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openidtrue'];
		//获取openid
		//广告显示控制
		if($reply['homepictime']>0){
			if($reply['homepictype']==1 && $_GPC['homepic']!="yes"){
				include $this->template('homepictime');
				exit;
			}
			if((empty($_COOKIE['stonefish_fighting_hometime'.$rid]) || $_COOKIE["stonefish_fighting_hometime".$rid]<=time()) && $_GPC['homepic']!="yes"){
				switch ($reply['homepictype']){
				    case 2:
				        setcookie("stonefish_fighting_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 day"))), strtotime(date("Y-m-d",strtotime("+1 day"))));
				        break;
					case 3:
				        setcookie("stonefish_fighting_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 week"))), strtotime(date("Y-m-d",strtotime("+7 week"))));
				        break;
					case 4:
				        setcookie("stonefish_fighting_hometime".$rid, strtotime(date("Y-m-d",strtotime("+1 year"))), strtotime(date("Y-m-d",strtotime("+1 year"))));
				        break;
				}
				include $this->template('homepictime');
				exit;
			}			
		}		
        //广告显示控制
		if(!empty($_COOKIE['stonefish_userinfo']) && $_W['account']['level']<4){
			$appUrl=$this->createMobileUrl($entrytype, array('rid' => $rid,'fromuser' => $_GPC['from_user'],'iid' => $iid),true);
			$appUrl=substr($appUrl,2);
			$url = $_W['siteroot'] ."app/".$appUrl;
			header("location: $url");
		    exit;
		}else{
			$setting = $this->module['config'];
		    //认证服务号
		    //认证服务号
		    if($_W['account']['level']==4){
			    $fans = pdo_fetch("select * from " . tablename('mc_mapping_fans') . " where uniacid = :uniacid and acid = :acid and openid = :openid order by `fanid` desc", array(':uniacid' => $uniacid, ':acid' => $acid, ':openid' => $from_user));
			    $appid = $_W['account']['key'];
                $secret = $_W['account']['secret'];
				if(intval($_W['fans']['follow'])){
			        load()->classs('weixin.account');
		            $accObj= WeixinAccount::create($acid);
		            $access_token = $accObj->fetch_token();
			        load()->func('communication');
			        $oauth2_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			        $content = ihttp_get($oauth2_code);
			        $token = @json_decode($content['content'], true);
			        setcookie("stonefish_userinfo", iserializer($token), time()+3600*24*$setting['stonefish_oauth_time']);
				    setcookie("stonefish_userinfo_power", $reply['power'], time()+3600*24*$setting['stonefish_oauth_time']);
			        //判断是否关注
					if(empty($fans)){
					    //平台没有此粉丝数据重新写入数据，一般不会出现这个问题
					    $rec = array();
			            $rec['acid'] = $acid;
			            $rec['uniacid'] = $uniacid;
			            $rec['uid'] = 0;
			            $rec['openid'] = $token['openid'];
			            $rec['salt'] = random(8);
				        $rec['follow'] = 1;
				        $rec['followtime'] = $token['subscribe_time'];
				        $rec['unfollowtime'] = 0;
					    $settings = uni_setting($uniacid, array('passport'));
					    if (!isset($settings['passport']) || empty($settings['passport']['focusreg'])) {
						    $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $uniacid));
						    $data = array(
					            'uniacid' => $uniacid,
					            'email' => md5($token['openid']).'@00393.com',
					            'salt' => random(8),
					            'groupid' => $default_groupid,
								'avatar' => rtrim($token['headimgurl'],'0').'132',
					            'createtime' => TIMESTAMP,
				            );
				            $data['password'] = md5($token['openid'] . $data['salt'] . $_W['config']['setting']['authkey']);
				            pdo_insert('mc_members', $data);
				            $rec['uid'] = pdo_insertid();
						    $fans['uid'] = $rec['uid'];
			            }
			            pdo_insert('mc_mapping_fans', $rec);					
					    //平台没有此粉丝数据重新写入数据，一般不会出现这个问题
				    }
				    $appUrl=$this->createMobileUrl($entrytype, array('rid' => $rid,'fromuser' => $_GPC['from_user'],'iid' => $iid),true);
			        $appUrl=substr($appUrl,2);
			        $url = $_W['siteroot'] ."app/".$appUrl;
			        header("location: $url");
		            exit;
			    }
			    if(!empty($_COOKIE['stonefish_userinfo'])){
				    $appUrl=$this->createMobileUrl($entrytype, array('rid' => $rid,'fromuser' => $_GPC['from_user'],'iid' => $iid),true);
				    $appUrl=substr($appUrl,2);
				    $url = $_W['siteroot'] ."app/".$appUrl;
				    header("location: $url");
		   	        exit;
		        }elseif($reply['power']==2){
				    $appUrl= $this->createMobileUrl('auth2', array('entrytype' => $entrytype,'rid' => $rid,'from_user' => $_GPC['from_user'],'iid' => $iid,'power' => $reply['power']),true);
		            $appUrl = substr($appUrl,2);
                    $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;
		            //snsapi_base为只获取OPENID,snsapi_userinfo为获取头像和昵称
			        $scope = $reply['power']==1 ? 'snsapi_base' : 'snsapi_userinfo';
                    $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=".$scope."&state=1#wechat_redirect";
                    header("location: $oauth2_code");
		            exit;
			    }
		    }
		    //认证服务号
		    //非认证服务号和认证服务号未关注粉丝
            //不是认证号又没有借用服务号获取头像昵称可认证服务号未关注用户
		    if($setting['stonefish_fighting_oauth']==0){
				if(!isset($_COOKIE["user_oauth2_wuopenid"]) && $_W['account']['level']!=4){
				   	//设置cookie信息
			    	setcookie("user_oauth2_wuopenid", time(), time()+3600*24*$setting['stonefish_oauth_time']);
			   	}
			    $appUrl=$this->createMobileUrl($entrytype, array('rid' => $rid,'fromuser' => $_GPC['from_user'],'iid' => $iid),true);
			   	$appUrl=substr($appUrl,2);
			   	$url = $_W['siteroot'] ."app/".$appUrl;
			    header("location: $url");
		        exit;
			}
		    //不是认证号又没有借用服务号获取头像昵称可认证服务号未关注用户			
		    //不是认证号 借用服务号获取头像昵称
            if ($setting['stonefish_fighting_oauth']==1 && !empty($_W['oauth_account']['key']) && !empty($_W['oauth_account']['secret'])) { // 判断是否是借用设置
                $appid = $_W['oauth_account']['key'];
                $secret = $_W['oauth_account']['secret'];
            }
			if ($setting['stonefish_fighting_oauth']==2 && !empty($setting['appid']) && ! empty($setting['secret'])) { // 判断是否是借用设置
                $appid = $setting['appid'];
                $secret = $setting['secret'];
            }
		    $appUrl= $this->createMobileUrl('auth2', array('entrytype' => $entrytype,'rid' => $rid,'from_user' => $_GPC['from_user'],'iid' => $iid,'power' => $reply['power']),true);
		    $appUrl = substr($appUrl,2);
            $redirect_uri = $_W['siteroot'] ."app/".$appUrl ;
		    //snsapi_base为只获取OPENID,snsapi_userinfo为获取头像和昵称
			$scope = $reply['power']==1 ? 'snsapi_base' : 'snsapi_userinfo';
            $oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=".$scope."&state=1#wechat_redirect";
            header("location: $oauth2_code");
		    exit;
		    //不是认证号 借用服务号获取头像昵称
		    //非认证服务号和认证服务号未关注粉丝
		}
	}
	//进入页
	//帮助页
	public function doMobileShareview() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uid = $_GPC['uid'];
		$uniacid = $_W['uniacid'];       
		$fromuser = authcode(base64_decode($_GPC['fromuser']), 'DECODE');
		$page_fromuser = $_GPC['fromuser'];		
		$acid = $_W['acid'];
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));		
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
		//活动状态
		$this->check_reply($reply);
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数		
		if(!empty($fromuser)) {
			//获取粉丝信息
			if($reply['power']==2){
			    $userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'shareview');
		    }
			//获取粉丝信息
			//参与分享人信息
		    $fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $fromuser));
		    if(!empty($fans)){
			    $realname = empty($fans['realname']) ? $fans['nickname'] : $fans['realname'];
				if($fans['status']==0){
				    $this->message_tips($rid,'抱歉，活动中您的朋友可能有作弊行为已被管理员暂停屏蔽！请告之你的朋友〖'.$realname.'〗，Ta将不胜感激！by【'.$_W['account']['name'].'】');
			    }
		    }else{
			    $this->message_tips($rid,'抱歉，您的朋友没有参与本活动！请告之你的朋友，3秒后自动进入活动页！',url('entry//index',array('m'=>'stonefish_fighting','rid'=>$rid)));
		    }
		}
		if($from_user!=$fromuser){
            $userinfofelp = pdo_fetch("select id,viewnum FROM " . tablename('stonefish_fighting_sharedata') . " where uniacid=:uniacid and rid=:rid and fromuser=:fromuser and from_user=:from_user order by visitorstime desc", array(':uniacid' => $uniacid,':rid' => $rid,":fromuser" => $fromuser,":from_user" => $from_user));
			if(empty($userinfofelp)){
				$insert = array(
                    'rid' => $rid,
                    'uniacid' => $uniacid,
                    'from_user' => $from_user,
				    'fromuser' => $fromuser,
				    'avatar' => $userinfo['headimgurl'],
				    'nickname' => $userinfo['nickname'],
				    'visitorsip'=> CLIENT_IP,
                    'visitorstime' => TIMESTAMP,
				    'viewnum' => 1
                );
				pdo_insert('stonefish_fighting_sharedata', $insert); // 记录助力人
            }else{
				pdo_update('stonefish_fighting_sharedata', array('viewnum' => $userinfofelp['viewnum'] + 1), array('id' => $userinfofelp['id']));
			}
			//记录分享
			//增加分享人分享量
			$sharenum = pdo_fetchcolumn("select count(id) from ".tablename('stonefish_fighting_sharedata')." where uniacid= :uniacid and fromuser= :fromuser and rid= :rid", array(':uniacid' => $uniacid,':rid' => $rid,':fromuser' => $fromuser));			
			pdo_update('stonefish_fighting_fans', array('sharenum' => $sharenum), array('uniacid' => $uniacid,'from_user' => $fromuser,'rid' => $rid));
			//增加分享人分享量
		}
		header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $this->createMobileUrl('index', array('rid' => $rid,'uid' => $uid)) . "");
        exit();
		include $this->template('share');
	}
	//帮助页
	//活动首页
	public function doMobileindex() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uid = intval($_GPC['uid']);
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }
        $reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$exchange = pdo_fetch("select * FROM ".tablename("stonefish_fighting_exchange")." where rid = :rid", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
        //活动状态
		$this->check_reply($reply);
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数		
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
        //获得关键词
        $reply['keyword']=  $this->rule_keyword($rid);
        //获得关键词		
		//获取openid以及头像昵称
		if(empty($from_user)) {
		    //没有获取openid跳转至引导页
            if (!empty($share['share_url'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: " . $share['share_url'] . "");
                exit();
            }else{
				$this->message_tips($rid,'请关注公众号再参与活动');
			}
			//没有获取openid跳转至引导页			           
		}else{
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			if($reply['issubscribe']>=1 && $_W['fans']['follow']==0){
			    //没有关注粉丝跳转至引导页
				if (!empty($share['share_url'])) {
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: " . $share['share_url'] . "");
                    exit();
                }else{
				    $this->message_tips($rid,'请关注公众号再参与活动');
			    }
				//没有关注粉丝跳转至引导页
			}
			//查询是否为关注用户并查询是否需要关注粉丝参与活动否则跳转至引导页
			//验证是否为会员
			if($reply['issubscribe']>=2){
				$members = pdo_fetch("select `status`,`groupid`,`departmentid` FROM ".tablename('stonefish_member')." where `uniacid`=:uniacid AND `uid` = :uid",array(':uniacid' => $_W['uniacid'],':uid' => $_W['member']['uid']));
				$profile = mc_fetch($_W['member']['uid'], array('mobile'));
				if(!empty($members) && $members['status']==0) {
				    $this->message_tips($rid,'会员已被锁定，请联系管理员');
				}
				if($reply['issubscribe']==3 && empty($profile['mobile'])) {
				    $this->message_tips($rid,'请先验证成为会员才能参与活动！',url('entry//member',array('m'=>'stonefish_member','fstatus'=>'mobile','url'=>url('entry//index',array('m'=>'stonefish_fighting','rid'=>$rid)))));
				}
				if($reply['issubscribe']==4 && empty($members['groupid'])) {
				    $this->message_tips($rid,'请先验证成为会员才能参与活动！',url('entry//member',array('m'=>'stonefish_member','fstatus'=>'groupid','url'=>url('entry//index',array('m'=>'stonefish_fighting','rid'=>$rid)))));
				}
				if($reply['issubscribe']==5 && empty($members['departmentid'])) {
				    $this->message_tips($rid,'请先验证成为会员才能参与活动！',url('entry//member',array('m'=>'stonefish_member','fstatus'=>'departmentid','url'=>url('entry//index',array('m'=>'stonefish_fighting','rid'=>$rid)))));
				}
				if(empty($members)) {
				    $this->message_tips($rid,'请先验证成为会员才能参与活动！',url('entry//member',array('m'=>'stonefish_member','url'=>url('entry//index',array('m'=>'stonefish_fighting','rid'=>$rid)))));
				}
			}
			//验证是否为会员
			//获得用户资料
		    if($_W['member']['uid']){
			    $profile = mc_fetch($_W['member']['uid'], array('avatar','nickname','realname','mobile','groupid','qq','email','address','gender','telephone','idcard','company','occupation','position'));
		    }
		    //获得用户资料
			//验证系统会员组
			if($reply['issubscribe']==6){
				$grouparr = (array)iunserializer($reply['sys_users']);
				if(!in_array($profile['groupid'], $grouparr)) {
					$this->message_tips($rid,$reply['sys_users_tips']);
				}
			}
			//验证系统会员组
			//获取粉丝信息
			if($reply['power']==2){
			    $userinfo = $this->get_userinfo($reply['power'],$rid,$iid,$page_fromuser,'index');
		    }
			//获取粉丝信息
		}
		//获取openid以及头像昵称
		//查询是否参与活动并更新头像和昵称$
		$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		//排名
		$myrank = pdo_fetchcolumn("select count(*) FROM ".tablename('stonefish_fighting_fans') ." where last_credit>=:last_credit and uniacid=:uniacid",array(":uniacid"=>$uniacid,":last_credit"=>$fans['last_credit']));
		if(!empty($fans)){
			if($fans['status']==0){
				$real_name = empty($fans['realname']) ? $fans['nickname'] : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$real_name.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//更新分享量
			$fans['sharenum'] = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_fighting_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			pdo_update('stonefish_fighting_fans', array('sharenum' => $fans['sharenum']), array('id' => $fans['id']));
			//更新分享量
			//更新头像和昵称
			if($reply['power']==2){
				pdo_update('stonefish_fighting_fans', array('avatar' => $userinfo['headimgurl'], 'nickname' => $userinfo['nickname']), array('id' => $fans['id']));
			}
			//更新头像和昵称
			//查询是否需要弹出填写资料
			if($fans['awardnum']){
				//自动读取会员信息存入FANS表中
				$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
				foreach ($ziduan as $ziduans) {
					if($exchange['is'.$ziduans]){
			            if(!empty($profile[$ziduans]) && empty($fans[$ziduans])){
				            if($exchange['isfans']==2){
							    pdo_update('stonefish_fighting_fans', array($ziduans => $profile[$ziduans]), array('id' => $fans['id']));
						    }else{
							    $$ziduans = true;
						    }
				        }else{
					        if(empty($fans[$ziduans])){
						        $$ziduans = true;
						    }
					    }
			        }
				}
				if($realname || $mobile || $qq || $email || $address || $gender || $telephone || $idcard || $company || $occupation || $position){
			       $isfans = true;
			    }
				//自动读取会员信息存入FANS表中
			}
			//查询是否需要弹出填写资料
		}else{
			$isfans = true;
		}
		//查询是否参与活动并更新头像和昵称
		//增加人数，和浏览次数
        pdo_update('stonefish_fighting_reply', array('viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
		//增加人数，和浏览次数		
		//参数重命名
		$isfansname = explode(',',$exchange['isfansname']);
		//参数重命名
		//判断是否中奖
		if($reply['prizestype']==2){
			$reply['prizes_type'] = 'stonefish_bigwheel';
			$prizeslist = pdo_fetch("select * FROM ".tablename('stonefish_fighting_prizeslist')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			$prizesnum = $prizeslist['prizesnum']-$prizeslist['usecount'];
			if($prizeslist['prizesnum']){
				$fansaward = pdo_fetch("select * from ".tablename('stonefish_bigwheel_fansaward')." where rid = :rid and uniacid = :uniacid and zhongjiang>=1 and from_user = :from_user", array(':rid' => $reply['urlrid'], ':uniacid' => $uniacid, ':from_user' => $from_user));
			}
		}
		if($reply['prizestype']==3){
			$reply['prizes_type'] = 'stonefish_scratch';
			$prizeslist = pdo_fetch("select * FROM ".tablename('stonefish_fighting_prizeslist')." where uniacid = :uniacid and rid = :rid and from_user = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			$prizesnum = $prizeslist['prizesnum']-$prizeslist['usecount'];
			if($prizeslist['prizesnum']){
				$fansaward = pdo_fetch("select * from ".tablename('stonefish_scratch_fansaward')." where rid = :rid and uniacid = :uniacid and zhongjiang>=1 and from_user = :from_user", array(':rid' => $reply['urlrid'], ':uniacid' => $uniacid, ':from_user' => $from_user));
			}
		}
		//判断是否中奖
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		//分享信息
		if($this->Weixin()){
			include $this->template('index');
		}else{
			$this->Weixin();
		}
    }
	//活动首页
	//用户注册
	public function doMobileRegfans() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        //获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		$uniacid = $_W['uniacid'];
		//规则判断
        $reply = pdo_fetch("select * FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$exchange = pdo_fetch("select * FROM ".tablename("stonefish_fighting_exchange")." where rid = :rid", array(':rid' => $rid));
        if ($reply == false) {
            $this->json_encode(array("success"=>2, "msg"=>'规则出错！...'));
        }
        if($reply['isshow'] != 1){
            $this->json_encode(array("success"=>2, "msg"=>'活动暂停，请稍后...'));
        }
        if ($reply['starttime'] > time()) {
            $this->json_encode(array("success"=>2, "msg"=>'活动还没有开始呢，请等待...'));
        }
        if ($reply['endtime'] < time()) {
            $this->json_encode(array("success"=>2, "msg"=>'活动已经结束了，下次再来吧！'));
        }
        if ($reply['issubscribe']>=1&&$_W['fans']['follow']==0) {
            $this->json_encode(array("success"=>2, "msg"=>'请先关注公共账号再来参与活动！详情请查看规则！'));
        }
		//规则判断
		$uid = pdo_fetchcolumn("select uid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$from_user,":uniacid"=>$uniacid));
		$profile = mc_fetch($uid, array('avatar','nickname','realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position'));
        //判断是否参与过
		$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			$this->json_encode(array("success"=>2, "msg"=>'已参与过本活动，请勿重复注册！'));
		}else{
			$fansdata = array(
                'rid' => $rid,
				'uniacid' => $uniacid,
                'from_user' => $from_user,					
				'avatar' => $_GPC['avatar'],
				'nickname' => $_GPC['nickname'],
                'createtime' => time(),
            );
            pdo_insert('stonefish_fighting_fans', $fansdata);
            $fans['id'] = pdo_insertid();
			//发送消息模板之参与模板
			if($exchange['tmplmsg_participate']){
				$this->seed_tmplmsg($from_user,$exchange['tmplmsg_participate'],$rid,array('do' =>'index', 'nickname' =>$_GPC['nickname']));
			}
			//发送消息模板之参与模板
			//自动读取会员信息存入FANS表中
			$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
			load()->model('mc');
			foreach ($ziduan as $ziduans){
				if($exchange['is'.$ziduans]){
					if(!empty($_GPC[$ziduans])){
				        pdo_update('stonefish_fighting_fans', array($ziduans => $_GPC[$ziduans]), array('id' => $fans['id']));
				        if($exchange['isfans']){				            
                            if($ziduans=='email'){
								mc_update($_W['member']['uid'], array('email' => $_GPC['email']));
							}else{
								mc_update($_W['member']['uid'], array($ziduans => $_GPC[$ziduans],'email' => $profile['email']));
							}
				        }
					}
			    }
		    }
		    //自动读取会员信息存入FANS表中
			//增加人数，和浏览次数
            pdo_update('stonefish_fighting_reply', array('fansnum' => $reply['fansnum'] + 1, 'viewnum' => $reply['viewnum'] + 1), array('id' => $reply['id']));
			$data = array(
                'success' => 1,
				'msg' => '成功报名活动,快来答题吧!',
            );			
		}
		//判断是否参与过
		$this->json_encode($data);
    }
	//用户注册	
	//分享成功
	public function doMobileShare_confirm() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$uniacid = $_W['uniacid'];
		$from_user = authcode(base64_decode($_GPC['from_user']), 'DECODE');
		$fans = pdo_fetch("select * from " . tablename('stonefish_fighting_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if ($fans == true) {
			//保存分享次数
			pdo_update('stonefish_fighting_fans', array('share_num' => $fans['share_num']+1,'sharetime' => time()), array('id' => $fans['id']));
			$data = array(
                'msg' => '分享次数保存成功！',
                'success' => 1,
            );
		}else{
			$data = array(
                'msg' => '还没有参与活动呀!',
                'success' => 0,
            );
		}
        $this->Json_encode($data);
    }
	//分享成功
	//活动规则
	public function doMobileRule() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }		
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));		
        //活动状态
		$this->check_reply($reply);		
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//查询奖品设置
		if($reply['prizestype']==2 && $reply['urlrid']){
			$prize = pdo_fetchall("select * FROM " . tablename('stonefish_bigwheel_prize') . " where rid = :rid ORDER BY `break` asc", array(':rid' => $reply['urlrid']));
		}
		if($reply['prizestype']==3 && $reply['urlrid']){
			$prize = pdo_fetchall("select * FROM " . tablename('stonefish_scratch_prize') . " where rid = :rid ORDER BY `break` asc", array(':rid' => $reply['urlrid']));
		}
		if($this->Weixin()){
			include $this->template('rule');
		}else{
			$this->Weixin();
		}
    }
	//活动规则
	//答题页
	public function doMobilefighting() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$qid = intval($_GPC['qid']);
		$istheanswer = intval($_GPC['istheanswer']);
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
		$nowtime = strtotime(date('Y-m-d'));
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }		
        $reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$exchange = pdo_fetch("select * FROM ".tablename("stonefish_fighting_exchange")." where rid = :rid", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
        //活动状态
		$this->check_reply($reply);
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数		
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid

		//查询是否参与活动并更新头像和昵称$
		$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(!empty($fans)){
			if($fans['status']==0){
				$realname = empty($fans['realname']) ? $fans['nickname'] : $fans['realname'];
				$this->message_tips($rid,'抱歉，活动中您〖'.$realname.'〗可能有作弊行为已被管理员暂停屏蔽！请联系【'.$_W['account']['name'].'】管理员');
			}
			//更新分享量
			$fans['sharenum'] = pdo_fetchcolumn("select count(id) FROM ".tablename('stonefish_fighting_sharedata')." where uniacid = :uniacid and rid = :rid and fromuser = :from_user", array(':uniacid' => $uniacid,':rid' => $rid,':from_user' => $from_user));
			pdo_update('stonefish_fighting_fans', array('sharenum' => $fans['sharenum']), array('id' => $fans['id']));
			//更新分享量
		}else{
			$this->message_tips($rid,'请先报名才能参与活动！',url('entry//entry',array('m'=>'stonefish_fighting','rid'=>$rid,'entrytype' => 'index')));
		}
		//查询是否参与活动并更新头像和昵称
		//更新当日次数
        if ($fans['last_time'] < $nowtime) {
            $fans['todaynum'] = 0;
			pdo_update('stonefish_fighting_fans', array('todaynum' => 0), array('id' => $fans['id']));
        }
		//更新当日次数
		//判断总次数超过限制,一般情况不会到这里的，考虑特殊情况,回复提示文字msg，便于测试
		if($_GPC['istheanswer']!=''){
			$fans['todaynum'] = $fans['todaynum']-1;
			$fans['totalnum'] = $fans['totalnum']-1;
		}
		if($fans['todaynum']>=$reply['number_days'] && $reply['number_days'] > 0){
			$this->message_tips($rid,'今天您已答过了，不能再参与了!',url('entry//rank',array('m'=>'stonefish_fighting','rid'=>$rid)));
		}
        if ($fans['totalnum'] >= $reply['number_times'] && $reply['number_times'] > 0) {
		   $this->message_tips($rid,'您超过参与总次数了，不能再参与了!',url('entry//rank',array('m'=>'stonefish_fighting','rid'=>$rid)));
        }
		//跳过问是否扣除积分
		if($_GPC['skip'] && $reply['marking']){
			$daylogs = pdo_fetch("select * from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".$nowtime."' order by id desc limit 1");
			if(!empty($daylogs)){
				pdo_update('stonefish_fighting_logs', array('jumpannum'=>$daylogs['jumpannum']+1, 'questionid'=>$daylogs['questionid'].','.$_GPC['skip'], 'questionids'=>$daylogs['questionids'].',j', 'createtime'=>time()), array('id'=>$daylogs['id']));
			}else{
				$insertlog = array(
                    'rid' => $rid,
				    'uniacid' => $uniacid,
                    'from_user' => $from_user,
				    'questionid' => $_GPC['skip'],
					'questionids' => 'j',
					'jumpannum' => 1,
					'createtime' => time(),
                );
				pdo_insert('stonefish_fighting_logs', $insertlog);
			}
			//扣除积分
			$daylogs = pdo_fetch("select * from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".$nowtime."' order by id desc limit 1");
			pdo_update('stonefish_fighting_fans', array('last_credit'=>$fans['last_credit']-$reply['marking'], 'day_credit'=>$fans['day_credit']-$reply['marking']), array('id'=>$fans['id']));
			pdo_update('stonefish_fighting_logs', array('day_credit'=>$daylogs['day_credit']-$reply['marking']), array('id'=>$daylogs['id']));
		}
		//跳过问是否扣除积分
		//是否还有答题数量
		$todayannum = pdo_fetch("select todayannum from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".$nowtime."' order by id desc limit 1");
		if($todayannum['todayannum']>=$reply['questionnum'] && $_GPC['istheanswer']!=''){
			//没有问题了或到达数量，进入排行页或抽奖
			//判断是否有抽奖机会
			$prizeslist = pdo_fetch("select * from ".tablename('stonefish_fighting_prizeslist')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."'");
			if($reply['prizestype']>=2){
				if($reply['prizestype']=2){
					$prizestype = 'stonefish_bigwheel';
				}else{
					$prizestype = 'stonefish_scratch';
				}
				if($reply['premise']==1){
					$prizesdata = array(
                        'rid' => $rid,
				        'uniacid' => $uniacid,
                        'from_user' => $from_user,
						'prizetype' => $prizestype,
						'urlrid' => $reply['urlrid'],
						'prizesnum' => $reply['prizesnum'],
                    );
					if(empty($prizeslist)){
						pdo_insert('stonefish_fighting_prizeslist', $prizesdata);
					}else{
						pdo_update('stonefish_fighting_prizeslist', array('prizesnum' => $prizeslist['prizesnum']+$reply['prizesnum']),array('id'=>$prizeslist['id']));
					}
					$this->message_tips($rid,'恭喜获得 '.$reply['prizesnum'].' 次抽奖机会，正在转向抽奖页!',url('entry//entry',array('m'=>$prizestype,'rid'=>$reply['urlrid'],'entrytype'=>'index')));
				}
				if($reply['premise']==2){
					$daylogs = pdo_fetch("select * from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".$nowtime."' order by id desc limit 1");
					if(strpos($daylogs['questionids'],'n')===false && strpos($daylogs['questionids'],'w')===false && strpos($daylogs['questionids'],'t')===false && strpos($daylogs['questionids'],'j')===false && strpos($daylogs['questionids'],'b')===false){
						$prizesdata = array(
                            'rid' => $rid,
				            'uniacid' => $uniacid,
                            'from_user' => $from_user,
						    'prizetype' => $prizestype,
						    'urlrid' => $reply['urlrid'],
						    'prizesnum' => $reply['prizesnum'],
                        );
					    if(empty($prizeslist)){
						    pdo_insert('stonefish_fighting_prizeslist', $prizesdata);
					    }else{
						    pdo_update('stonefish_fighting_prizeslist', array('prizesnum' => $prizeslist['prizesnum']+$reply['prizesnum']),array('id'=>$prizeslist['id']));
					    }
						$this->message_tips($rid,'恭喜全部答对！获得 '.$reply['prizesnum'].' 次抽奖机会，正在转向抽奖页!',url('entry//entry',array('m'=>$prizestype,'rid'=>$reply['urlrid'],'entrytype'=>'index')));
					}
				}
			}			
			//判断是否有抽奖机会
			$this->message_tips($rid,'您今天本次问题已答完!',url('entry//myanswer',array('m'=>'stonefish_fighting','rid'=>$rid)));
		}
		//是否还有答题数量
		//获取新的问题
		$question = $this->requireQuestions($rid,$istheanswer);
		if(!empty($question)){
			$istheanswer = 1;			
		}else{
			$this->message_tips($rid,'您今天问题已答完了!',url('entry//rank',array('m'=>'stonefish_fighting','rid'=>$rid)));
		}
		//获取新的问题
        //分享信息
        $sharelink = $_W['siteroot'] .'app/'.substr($this->createMobileUrl('entry', array('rid' => $rid,'from_user' => $page_from_user,'entrytype' => 'shareview')),2);
        $sharetitle = empty($share['share_title']) ? '欢迎参加活动' : $share['share_title'];
        $sharedesc = empty($share['share_desc']) ? '亲，欢迎参加活动，祝您好运哦！！' : str_replace("\r\n"," ", $share['share_desc']);
		$sharetitle = $this->get_share($rid,$from_user,$sharetitle);
		$sharedesc = $this->get_share($rid,$from_user,$sharedesc);
		if(!empty($share['share_img'])){
		    $shareimg = toimage($share['share_img']);
		}else{
		    $shareimg = toimage($reply['start_picurl']);
		}
		//分享信息
		if($this->Weixin()){
			include $this->template('fighting');
		}else{
			$this->Weixin();
		}
    }
	//答题页
	//获取随机问题
	function requireQuestions($rid,$istheanswer){
	//回答过的问题不再显示
	    global $_GPC, $_W;		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
		$nowtime = strtotime(date('Y-m-d'));
		$reply = pdo_fetch("select question,questiontype,tid,number_days from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		//获取openid
		$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if($reply['notquestionnum']==0){
			$nowtime = strtotime(date('Y-m-d'));
			$where =" and createtime>'".$nowtime."'";
		}
		$sql_notid = "SELECT GROUP_CONCAT(questionid) AS questionid FROM ".tablename('stonefish_fighting_logs')." WHERE  `rid`=:rid AND `from_user`=:from_user ".$where." limit 1";
		$notid = pdo_fetch($sql_notid, array(':rid' => $rid, ':from_user' => $from_user));
		if(!empty($notid['questionid'])){
			$notids = $notid['questionid'];
		}else{
			$notids = "0";
		}		
		//查找以前回答过的问题　主要为了不重复出现问题
		if($reply['question']==0){
			if ($reply['questiontype']=='0'){
        	    $sql_question = "SELECT * FROM " . tablename('stonefish_fighting_questionbank') . " WHERE id not in(".$notids.") ORDER BY RAND() LIMIT 1";
		    }else{
			    $sql_question = "SELECT * FROM " . tablename('stonefish_fighting_questionbank') . " WHERE id not in(".$notids.") and questiontype = '".$reply['questiontype']."'  ORDER BY RAND() LIMIT 1";
		    }
		}else{
			$tid = ltrim(rtrim($reply['tid'], ","),",");
			//判断多题库类别是否使用完
			$sql_daynotid = "SELECT GROUP_CONCAT(questionid) AS questionid FROM ".tablename('stonefish_fighting_logs')." WHERE  `rid`=:rid AND `from_user`=:from_user";
		    $daynotid = pdo_fetch($sql_daynotid, array(':rid' => $rid, ':from_user' => $from_user));
			if(!empty($daynotid['questionid'])){
				foreach (explode(',',$daynotid['questionid']) as $notid){
				    $questiontype= pdo_fetchcolumn("select questiontype from ".tablename('stonefish_fighting_questionbank')." where id='".$notid."'");
				    $idlist[$questiontype]++;
				    $typenum = pdo_fetchcolumn("select typenum from ".tablename('stonefish_fighting_questionlist')." where typeid='".$questiontype."' and rid='".$rid."' and uniacid='".$uniacid."'");
				    if($idlist[$questiontype]>=$typenum){
					    $tid = str_replace($questiontype.',', '', $tid);
				    }
			    }
			}
			//判断多题库类别是否使用完
			$sql_question = "SELECT * FROM " . tablename('stonefish_fighting_questionbank') . " WHERE id not in(".$notids.") and questiontype in(".$tid.")  ORDER BY RAND() LIMIT 1";
		}
        $question = pdo_fetch($sql_question);
		if(!empty($question)){
			//第一次进入写入记录
		    if($istheanswer!=1){
			    $insertlog = array(
                    'rid' => $rid,
				    'uniacid' => $uniacid,
                    'from_user' => $from_user,
					'questionid' => $question['id'],
					'questionids' => 'n',
					'todayannum' => 1,
				    'createtime' => time(),
                );
			    pdo_insert('stonefish_fighting_logs', $insertlog);
			    pdo_update('stonefish_fighting_fans', array('totalnum'=>$fans['totalnum']+1,'todaynum'=>$fans['todaynum']+1,'last_time'=>time()), array('id'=>$fans['id']));
		    }else{
			    $daylogs = pdo_fetch("select id,questionid,questionids,todayannum from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".strtotime(date('Y-m-d'))."' order by id desc");
			    $questionid = empty($daylogs['questionid']) ? $question['id'] : $daylogs['questionid'].','.$question['id'];
			    $questionids = empty($daylogs['questionids']) ? 'n' : $daylogs['questionids'].',n';
				pdo_update('stonefish_fighting_logs', array('questionid'=>$questionid,'questionids'=>$questionids,'todayannum'=>$daylogs['todayannum']+1),array('id'=>$daylogs['id']));
		    }
		    //第一次进入写入记录			
		}
		return $question;
	}
	//获取随机问题
	//获取答案
	public function doMobilegetAnswer() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
		$answer = $_GPC['answer'];
		$qestionid = intval($_GPC['qestionid']);
		$timeout = intval($_GPC['timeout']);
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->Json_encode(array('resultCode'=>3, 'msg' => '系统出错!'));
        }		
        $reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		//获取openid
		//查询是否参与活动
		$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where rid = :rid and uniacid = :uniacid and from_user= :from_user", array(':rid' => $rid, ':uniacid' => $uniacid, ':from_user' => $from_user));
		if(empty($fans)){
			$this->Json_encode(array('resultCode'=>3, 'msg' => '还没有参与活动!'));
		}else{
			$question = pdo_fetch("select * from ".tablename('stonefish_fighting_questionbank')." where id = :id", array(':id' => $qestionid));
			if(empty($question)){
				$this->Json_encode(array('resultCode'=>3, 'msg' => '问题出错!'));
			}else{
				//是否超量
				$nowtime = strtotime(date('Y-m-d'));
				$daylogs = pdo_fetch("select * from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' and createtime>='".$nowtime."' order by id desc limit 1");
				if($daylogs['todayannum']-1>=$reply['questionnum']){
					$this->Json_encode(array('resultCode'=>3, 'msg' => '出错了，答题量超过数量了!'));
				}
				if(end(explode(",",$daylogs['questionids']))!='n'){
					$this->Json_encode(array('resultCode'=>3, 'msg' => '出错了，您已答过此问题了，请勿返回重新答题！'));
				}
				//是否超量
				//是否已回答过此问题
				if($qestionid!=end(explode(",",$daylogs['questionid']))){
					pdo_update('stonefish_fighting_logs', array('questionids'=>rtrim($daylogs['questionids'],"n").'b'), array('id'=>$daylogs['id']));
					$this->Json_encode(array('resultCode'=>3, 'msg' => '出错了，偷偷返回看过答案再来回答问题，这样不道德呀!本次答题机会被惩罚没收！'));
				}
				//是否已回答过此问题
				//是否为正确答案
				$answertrue = 'ABCDEF';
				for($i=0;$i<strlen($answertrue);$i++){
					if($question['option'.$answertrue[$i].'true']==0 && !strstr($answer,$answertrue[$i])){
						$answer_is = 1;
					}elseif($question['option'.$answertrue[$i].'true']==1 && strstr($answer,$answertrue[$i])){
						$answer_is = 1;
					}else{
						$answer_is = 'w';
						break;
					}
				}
				//是否为正确答案
				//超时是否默认选择答案
				if($timeout==1){
					if($answer_is==1 && $reply['timeout']==1){
						$answer_is = 1;
					}else{
						$answer_is = 't';
					}
				}
				//超时是否默认选择答案
				//正确答案
				if($answer_is!=1){
					for($i=0;$i<strlen($answertrue);$i++){
					    if($question['option'.$answertrue[$i].'true']==1){
						    $answer_true .= $answertrue[$i];
					    }
				    }
				}
				//正确答案
				//写入记录日志
				if($answer_is==1){
					$rightannum = $daylogs['rightannum']+1;
					$wrongannum = $daylogs['wrongannum'];
				}else{
					$rightannum = $daylogs['rightannum'];
					$wrongannum = $daylogs['wrongannum']+1;
				}				
				
				if($daylogs['questionids']=='n'){
					$questionids = $answer_is;
				}else{
					$questionids = substr($daylogs['questionids'],0,strlen($daylogs['questionids'])-1).$answer_is;
				}				
				$insertlog = array(
					'questionids' => $questionids,
					'rightannum' => $rightannum,
                    'wrongannum' => $wrongannum,
					'createtime' => time(),
                );
				pdo_update('stonefish_fighting_logs', $insertlog, array('id'=>$daylogs['id']));
				//写入记录日志
				$day_credit = $question['figure']+$daylogs['day_credit'];
				$last_credit = $question['figure']+$fans['last_credit'];
				if($answer_is==1){
					//写入fans数据表每日以及总积分
				    pdo_update('stonefish_fighting_fans', array('day_credit'=>$day_credit, 'last_credit'=>$last_credit), array('id'=>$fans['id']));
					pdo_update('stonefish_fighting_logs', array('day_credit'=>$day_credit), array('id'=>$daylogs['id']));
				    //写入fans数据表每日以及总积分
					//正确答案
				    $this->Json_encode(array('resultCode'=>1));
				}else{
					if($answer_is=='w'){
						//错误答案
				        $this->Json_encode(array('resultCode'=>2, 'msg'=>$answer_true));
					}
					if($answer_is=='t'){
						//超时
				        $this->Json_encode(array('resultCode'=>0, 'msg'=>$answer_true));
					}
					$this->Json_encode(array('resultCode'=>2, 'msg'=>'奇怪'));
				}
			}
		}
	}
	//获取答案
	//排行榜
	public function doMobileRank() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));		
        //活动状态
		$this->check_reply($reply);		
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//排行榜
		$rankorder = 'last_credit';//awardnum奖品数量sharenum分享量sharepoint分享助力
		$rank = pdo_fetchall("select * from " . tablename('stonefish_fighting_fans') . " where rid = :rid and uniacid = :uniacid and totalnum>0 and status=1 order by ".$rankorder." desc,`id` asc limit ".$reply['viewranknum'], array(':rid' => $rid,':uniacid' => $uniacid));		
		//排行榜
		if($this->Weixin()){
			include $this->template('rank');
		}else{
			$this->Weixin();
		}
    }
	//排行榜
	//我的奖品
	public function doMobileMyaward() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));
        //活动状态
		$this->check_reply($reply);
		//活动状态
		//兑奖参数重命名
		$isfansname = explode(',',$exchange['isfansname']);
		//兑奖参数重命名
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//哪个模块
		if($reply['prizestype']==2){
			$othermodel = 'stonefish_bigwheel';
		}
		if($reply['prizestype']==3){
			$othermodel = 'stonefish_scratch';
		}
		//哪个模块
		$exchange = pdo_fetch("select * FROM ".tablename($othermodel."_exchange")." where rid = :rid", array(':rid' => $reply['urlrid']));
		//我的礼盒奖品
		$fans = pdo_fetch("select * from " . tablename($othermodel.'_fans') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user", array(':rid' => $reply['urlrid'], ':uniacid' => $uniacid, ':from_user' => $from_user));
		//查询是否需要弹出填写兑奖资料
		if($fans['awardnum']){
			//自动读取会员信息存入FANS表中
			$uid = pdo_fetchcolumn("select uid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$from_user,":uniacid"=>$uniacid));
			$profile = mc_fetch($uid, array('avatar','nickname','realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position'));
			$ziduan = array('realname','mobile','qq','email','address','gender','telephone','idcard','company','occupation','position');
			foreach ($ziduan as $ziduans) {
				if($exchange['is'.$ziduans]){
			        if(!empty($profile[$ziduans]) && empty($fans[$ziduans])){
				        if($exchange['isfans']==2){
							pdo_update('stonefish_fighting_fans', array($ziduans => $profile[$ziduans]), array('id' => $fans['id']));
						}else{
							$$ziduans = true;
						}
				    }else{
					    if(empty($fans[$ziduans])){
						    $$ziduans = true;
						}
					}
			    }
			}
			if($realname || $mobile || $qq || $email || $address || $gender || $telephone || $idcard || $company || $occupation || $position){
			    $isfans = true;
			}
			//自动读取会员信息存入FANS表中
		}
		//查询是否需要弹出填写兑奖资料
		$mylihe = pdo_fetchall("select tt.* from(
select * from ".tablename($othermodel.'_fansaward')." order by zhongjiang asc) as tt  where rid = :rid and uniacid = :uniacid and from_user = :from_user and zhongjiang>=1 GROUP BY prizeid order by `id` desc", array(':rid' => $reply['urlrid'],':uniacid' => $uniacid,':from_user' => $from_user));
		foreach ($mylihe as $mid => $mylihes) {
			$mylihe[$mid]['num'] = pdo_fetchcolumn("select count(id) from " . tablename($othermodel.'_fansaward') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user and zhongjiang=1 and prizeid='".$mylihes['prizeid']."'", array(':rid' => $reply['urlrid'],':uniacid' => $uniacid,':from_user' => $from_user));
			$mylihe[$mid]['numd'] = pdo_fetchcolumn("select count(id) from " . tablename($othermodel.'_fansaward') . " where rid = :rid and uniacid = :uniacid and from_user = :from_user and zhongjiang=2 and prizeid='".$mylihes['prizeid']."'", array(':rid' => $reply['urlrid'],':uniacid' => $uniacid,':from_user' => $from_user));
			$prize = pdo_fetch("select * from " . tablename($othermodel.'_prize') . " where id='".$mylihes['prizeid']."'");
			$mylihe[$mid]['prizepic'] = $prize['prizepic'];
			$mylihe[$mid]['prizerating'] = $prize['prizerating'];
			$mylihe[$mid]['prizename'] = $prize['prizename'];
			$mylihe[$mid]['prizetype'] = $prize['prizetype'];
			
			if(empty($mylihes['ticketname'])&&!empty($mylihes['ticketid'])){
				if($exchange['tickettype']==2){
				    $mylihe[$mid]['ticketname'] = pdo_fetchcolumn("select name FROM " . tablename('activity_coupon_password') . " where uniacid = :uniacid and id = :id", array(':uniacid' => $_W['uniacid'],':id' => $mylihes['ticketid']));
			    }
			    if($exchange['tickettype']==3){
				    $mylihe[$mid]['ticketname'] = pdo_fetchcolumn("select title FROM " . tablename('stonefish_branch_business') . " where uniacid = :uniacid and id = :id", array(':uniacid' => $_W['uniacid'],':id' => $mylihes['ticketid']));
			    }
			}
			$mylihe[$mid]['ticketid'] = empty($mylihe[$mid]['ticketid']) ? "0" : $mylihe[$mid]['ticketid'];
			$mylihe[$mid]['ticketname'] = empty($mylihe[$mid]['ticketname']) ? "没有选择" : $mylihe[$mid]['ticketname'];
		}
		//我的礼盒奖品
		//店员
		if($exchange['tickettype']==2){
			$shangjia = pdo_fetchall("select name as shangjianame,id FROM " . tablename('activity_coupon_password') . " where uniacid = :uniacid ORDER BY `id` asc", array(':uniacid' => $uniacid));
		}
		//商家网点
		if($exchange['tickettype']==3){
			$shangjia = pdo_fetchall("select title as shangjianame,id FROM " . tablename('stonefish_branch_business') . " where uniacid = :uniacid ORDER BY `id` DESC", array(':uniacid' => $uniacid));
		}
		if($this->Weixin()){
			include $this->template('myaward');
		}else{
			$this->Weixin();
		}
    }
	//我的奖品
	//本次答题记录
	public function doMobileMyanswer() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);		
		$uniacid = $_W['uniacid'];
		$acid = $_W['acid'];
		$nowtime = strtotime(date('Y-m-d'));
        if (empty($rid)) {
            $this->message_tips($rid,'抱歉，参数错误！');
        }
		$reply = pdo_fetch("select * from " . tablename('stonefish_fighting_reply') . " where rid = :rid order by `id` desc", array(':rid' => $rid));
		$template = pdo_fetch("select * from " . tablename('stonefish_fighting_template') . " where id = :id", array(':id' => $reply['templateid']));
		$share = pdo_fetch("select * from " . tablename('stonefish_fighting_share') . " where rid = :rid and acid = :acid", array(':rid' => $rid,':acid' => $acid));		
        //活动状态
		$this->check_reply($reply);		
		//活动状态
		//虚拟人数
		$this->xuni_time($reply);
		//虚拟人数
		//获取openid
		$openid = $this->get_openid();
		$from_user = $openid['openid'];
		$page_from_user = base64_encode(authcode($from_user, 'ENCODE'));
		//获取openid
		//我的本次答题记录
		$daylogs = pdo_fetchall("select * from ".tablename('stonefish_fighting_logs')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."' order by id desc");
		$my = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where uniacid='".$uniacid."' and rid= '".$rid."' and from_user='".$from_user."'");
		//我的本次答题记录
		if($this->Weixin()){
			include $this->template('myanswer');
		}else{
			$this->Weixin();
		}
    }
	//本次答题记录	
	//活动管理
	public function doWebManage() {
        global $_GPC, $_W;
        //查询是否有商户网点权限
		$modules = uni_modules($enabledOnly = true);
		$modules_arr = array();
		$modules_arr = array_reduce($modules, create_function('$v,$w', '$v[$w["mid"]]=$w["name"];return $v;'));
		if(in_array('stonefish_branch',$modules_arr)){
		    $stonefish_branch = true;
		}
		//查询是否有商户网点权限
		//查询是否填写系统参数
		$setting = $this->module['config'];
		if(empty($setting)){
			message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting',array('m' => 'stonefish_fighting')), 'error');
		}
		//查询是否填写系统参数
		load()->model('reply');		
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sql = "uniacid = :uniacid AND `module` = :module";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':module'] = 'stonefish_fighting';

        if (!empty($_GPC['keyword'])) {
            $sql .= ' AND `name` LIKE :keyword';
            $params[':keyword'] = "%{$_GPC['keyword']}%";
        }
        $list = reply_search($sql, $params, $pindex, $psize, $total);
        $pager = pagination($total, $pindex, $psize);

        if (!empty($list)) {
            foreach ($list as &$item) {
                $condition = "`rid`={$item['id']}";
                $item['keyword'] = reply_keywords_search($condition);
                $reply = pdo_fetch("select title, fansnum, viewnum, starttime, endtime, isshow FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ", array(':rid' => $item['id']));
				$item['fansnum'] = $reply['fansnum'];
                $item['viewnum'] = $reply['viewnum'];
                $item['starttime'] = date('Y-m-d H:i', $reply['starttime']);
                $endtime = $reply['endtime'] + 86399;
                $item['endtime'] = date('Y-m-d H:i', $endtime);
                $nowtime = time();
                if ($reply['starttime'] > $nowtime) {
                    $item['status'] = '<span class="label label-warning">未开始</span>';
                    $item['show'] = 1;
                } elseif ($endtime < $nowtime) {
                    $item['status'] = '<span class="label label-default ">已结束</span>';
                    $item['show'] = 0;
                } else {
                    if ($reply['isshow'] == 1) {
                        $item['status'] = '<span class="label label-success">已开始</span>';
                        $item['show'] = 2;
                    } else {
                        $item['status'] = '<span class="label label-default ">已暂停</span>';
                        $item['show'] = 1;
                    }
                }
                $item['isshow'] = $reply['isshow'];
				$item['title'] = $reply['title'];
            }
        }
        include $this->template('manage');
    }
	//活动管理
	//活动分析表
	public function doWebTrend() {
        global $_GPC, $_W;
		load()->func('tpl');
		//查询是否填写系统参数
		$setting = $this->module['config'];
		if(empty($setting)){
			message('抱歉，系统参数没有填写，请先填写系统参数！', url('profile/module/setting',array('m' => 'stonefish_fighting')), 'error');
		}
		//查询是否填写系统参数
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
        $rid = intval($_GPC['rid']);
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		$reply = pdo_fetch("select * FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		$reply['wentinum'] = pdo_fetchcolumn('SELECT COALESCE(SUM(todayannum),0) FROM ' . tablename('stonefish_fighting_logs') . ' WHERE rid = :rid AND uniacid = :uniacid', array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		$reply['helpnum'] = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_fighting_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid', array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//今日昨天关键指标
		$fansnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_fighting_fans') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		$lingqunum = pdo_fetchcolumn('SELECT COALESCE(SUM(todayannum),0) FROM ' . tablename('stonefish_fighting_logs') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		$helpnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_fighting_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid AND visitorstime >= :starttime AND visitorstime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')) - 86400, ':endtime' => strtotime(date('Y-m-d'))));
		
		$today_fansnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_fighting_fans') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		$today_lingqunum = pdo_fetchcolumn('SELECT COALESCE(SUM(todayannum),0) FROM ' . tablename('stonefish_fighting_logs') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime >= :starttime AND createtime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		$today_helpnum = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('stonefish_fighting_sharedata') . ' WHERE rid = :rid AND uniacid = :uniacid AND visitorstime >= :starttime AND visitorstime <= :endtime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => strtotime(date('Y-m-d')), ':endtime' => TIMESTAMP));
		//今日昨天关键指标
		$scroll = intval($_GPC['scroll']);
		$st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-30day');
	    $et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d'));
		if(empty($_GPC['datelimit']['start']) && $st!=$reply['starttime']){
			$st=$reply['starttime'];
		}
	    $starttime = min($st, $et);
	    $endtime = max($st, $et);
		$day_num = ($endtime - $starttime) / 86400 + 1;
	    $endtime += 86399;
		if($_W['isajax'] && $_W['ispost']) {
		    $days = array();
		    $datasets = array();
		    for($i = 0; $i < $day_num; $i++){
			    $key = date('m-d', $starttime + 86400 * $i);
			    $days[$key] = 0;
			    $datasets['flow1'][$key] = 0;
			    $datasets['flow2'][$key] = 0;
			    $datasets['flow3'][$key] = 0;
		    }

			$data = pdo_fetchall('SELECT createtime FROM ' . tablename('stonefish_fighting_fans') . ' WHERE uniacid = :uniacid AND rid = :rid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['createtime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow1'][$key]++;
			    }
		    }

			$data = pdo_fetchall('SELECT createtime FROM ' . tablename('stonefish_fighting_logs') . ' WHERE uniacid = :uniacid AND rid = :rid AND createtime >= :starttime AND createtime <= :endtime', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['createtime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow2'][$key]+=pdo_fetchcolumn('SELECT COALESCE(SUM(todayannum),0) FROM ' . tablename('stonefish_fighting_logs') . ' WHERE rid = :rid AND uniacid = :uniacid AND createtime = :starttime', array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':starttime' => $da['createtime']));
			    }
		    }
			
			$data = pdo_fetchall('SELECT visitorstime FROM ' . tablename('stonefish_fighting_sharedata') . ' WHERE uniacid = :uniacid AND rid = :rid AND visitorstime >= :starttime AND visitorstime <= :endtime', array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':starttime' => $starttime, ':endtime' => $endtime));
		    foreach($data as $da) {
			    $key = date('m-d', $da['visitorstime']);
			    if(in_array($key, array_keys($days))) {
				    $datasets['flow3'][$key]++;
			    }
		    }			
			

		    $shuju['label'] = array_keys($days);
		    $shuju['datasets'] = $datasets;
		
		    if ($day_num == 1) {
			    $day_num = 2;
			    $shuju['label'][] = $shuju['label'][0];
			
			    foreach ($shuju['datasets']['flow1'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow1']['-'] = $v;
			
			    foreach ($shuju['datasets']['flow2'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow2']['-'] = $v;
			
			    foreach ($shuju['datasets']['flow3'] as $ky => $va) {
				    $k = $ky;
				    $v = $va;
			    }
			    $shuju['datasets']['flow3']['-'] = $v;
		    }

		    $shuju['datasets']['flow1'] = array_values($shuju['datasets']['flow1']);
		    $shuju['datasets']['flow2'] = array_values($shuju['datasets']['flow2']);
		    $shuju['datasets']['flow3'] = array_values($shuju['datasets']['flow3']);
		    exit(json_encode($shuju));		
	    }
		
        include $this->template('trend');
    }
	//活动分析表
	//模板管理
	public function doWebTemplate() {
        global $_GPC, $_W;
		//活动模板
		$template = pdo_fetch("select * FROM " . tablename('stonefish_fighting_template') . " where uniacid=0 ORDER BY `id` asc");
		if(empty($template)){			
			$inserttemplate = array(
                'uniacid'          => 0,
				'title'            => '默认',
				'thumb'            => '../addons/stonefish_fighting/template/images/template.jpg',
				'fontsize'         => '12',
				'bgimg'            => '../addons/stonefish_fighting/template/images/bgimg.png',
				'bgcolor'          => '#fec303',
				'textcolor'        => '#666666',
				'textcolorlink'    => '#f3f3f3',
				'buttoncolor'      => '#ff540a',
				'buttontextcolor'  => '#f3f3f3',
				'rulecolor'        => '#fff6cd',
				'ruletextcolor'    => '#434343',
				'navcolor'         => '#fff6cd',
				'navtextcolor'     => '#434343',
				'navactioncolor'   => '#ff540a',
				'watchcolor'       => '#fce5cd',
				'watchtextcolor'   => '#cc0000',
				'awardcolor'       => '#ff5108',
				'awardtextcolor'   => '#ffffff',
				'awardscolor'      => '#9fc5e8',
				'awardstextcolor'  => '#134f5c',
			);
			pdo_insert('stonefish_fighting_template', $inserttemplate);			
		}
		//活动模板
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {     
            $where.=' and title=:title';
            $params[':title'] = $_GPC['keyword'];
        }		
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_template') . "  where (uniacid=:uniacid OR uniacid=0) " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_template') . " where (uniacid=:uniacid OR uniacid=0) " . $where . " order by id desc " . $limit, $params);
        include $this->template('template');
    }
	//模板管理
	//模板修改
	public function doWebTemplatepost() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_template')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['edit']) && empty($_GPC['fuzhi'])){
				message('无限修改', url('site/entry/template', array('m' => 'stonefish_fighting')), 'error');
			}
			if(empty($_GPC['title'])){
				message('模板名称必需输入', referer(), 'error');
			}
			if(!isset($_GPC['thumb'])){
				message('模板缩略图必需上传', referer(), 'error');
			}
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'title'            => $_GPC['title'],
				'thumb'            => $_GPC['thumb'],
				'fontsize'         => $_GPC['fontsize'],
				'bgimg'            => $_GPC['bgimg'],
				'bgcolor'          => $_GPC['bgcolor'],
				'textcolor'        => $_GPC['textcolor'],
				'textcolorlink'    => $_GPC['textcolorlink'],
				'buttoncolor'      => $_GPC['buttoncolor'],
				'buttontextcolor'  => $_GPC['buttontextcolor'],
				'rulecolor'        => $_GPC['rulecolor'],
				'ruletextcolor'    => $_GPC['ruletextcolor'],
				'navactioncolor'   => $_GPC['navactioncolor'],
				'navcolor'         => $_GPC['navcolor'],
				'navtextcolor'     => $_GPC['navtextcolor'],
				'watchcolor'       => $_GPC['watchcolor'],
				'watchtextcolor'   => $_GPC['watchtextcolor'],
				'awardscolor'      => $_GPC['awardscolor'],
				'awardstextcolor'  => $_GPC['awardstextcolor'],
				'awardcolor'       => $_GPC['awardcolor'],
				'awardtextcolor'   => $_GPC['awardtextcolor'],
		    );
			if(!empty($_GPC['edit'])){
				if(!empty($id)) {
				    pdo_update('stonefish_fighting_template', $data, array('id' => $id));
				    message('模板修改成功！', url('site/entry/template', array('m' => 'stonefish_fighting')), 'success');
			    }else{
				    pdo_insert('stonefish_fighting_template', $data);
				    message('模板添加成功！', url('site/entry/template', array('m' => 'stonefish_fighting')), 'success');
			    }
			}
			if(!empty($_GPC['fuzhi'])){
				$data['uniacid'] = $_W['uniacid'];
				pdo_insert('stonefish_fighting_template', $data);
				$id = pdo_insertid();
				message('模板复制成功！', url('site/entry/templatepost', array('m' => 'stonefish_fighting','id' => $id)), 'success');
			}					
		}
        include $this->template('templatepost');
    }
	//模板修改
	//模板删除
	public function doWebTemplatedel() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_template')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				pdo_delete('stonefish_fighting_template', array('id' => $id));
				message('模板删除成功', referer(), 'success');
			}else{
				message('活动不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//模板删除
	//消息模板管理
	public function doWebTmplmsg() {
        global $_GPC, $_W;
		$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {     
            $where.=' and template_name=:template_name';
            $params[':template_name'] = $_GPC['keyword'];
        }		
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_tmplmsg') . "  where uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_tmplmsg') . " where uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
        include $this->template('tmplmsg');
    }
	//消息模板管理
	//消息模板修改
	public function doWebTmplmsgpost() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_tmplmsg')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['template_name'])){
				message('消息模板名称必需输入', referer(), 'error');
			}
			if(empty($_GPC['template_id'])){
				message('消息模板ID必需输入', referer(), 'error');
			}
			if(empty($_GPC['first'])){
				message('消息模板标题必需输入', referer(), 'error');
			}
			if(empty($_GPC['keyword1'])){
				message('消息模板必需输入一个参数', referer(), 'error');
			}
			if(empty($_GPC['remark'])){
				message('消息模板必需输入备注', referer(), 'error');
			}
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'template_name'    => $_GPC['template_name'],
				'template_id'      => $_GPC['template_id'],
				'topcolor'         => $_GPC['topcolor'],
				'first'            => $_GPC['first'],
				'firstcolor'       => $_GPC['firstcolor'],
				'keyword1'         => $_GPC['keyword1'],
				'keyword2'         => $_GPC['keyword2'],
				'keyword3'         => $_GPC['keyword3'],
				'keyword4'         => $_GPC['keyword4'],
				'keyword5'         => $_GPC['keyword5'],
				'keyword6'         => $_GPC['keyword6'],
				'keyword7'         => $_GPC['keyword7'],
				'keyword8'         => $_GPC['keyword8'],
				'keyword9'         => $_GPC['keyword9'],
				'keyword10'        => $_GPC['keyword10'],
				'keyword1color'    => $_GPC['keyword1color'],
				'keyword2color'    => $_GPC['keyword2color'],
				'keyword3color'    => $_GPC['keyword3color'],
				'keyword4color'    => $_GPC['keyword4color'],
				'keyword5color'    => $_GPC['keyword5color'],
				'keyword6color'    => $_GPC['keyword6color'],
				'keyword7color'    => $_GPC['keyword7color'],
				'keyword8color'    => $_GPC['keyword8color'],
				'keyword9color'    => $_GPC['keyword9color'],
				'keyword10color'   => $_GPC['keyword10color'],
				'keyword1code'     => $_GPC['keyword1code'],
				'keyword2code'     => $_GPC['keyword2code'],
				'keyword3code'     => $_GPC['keyword3code'],
				'keyword4code'     => $_GPC['keyword4code'],
				'keyword5code'     => $_GPC['keyword5code'],
				'keyword6code'     => $_GPC['keyword6code'],
				'keyword7code'     => $_GPC['keyword7code'],
				'keyword8code'     => $_GPC['keyword8code'],
				'keyword9code'     => $_GPC['keyword9code'],
				'keyword10code'    => $_GPC['keyword10code'],
				'remark'           => $_GPC['remark'],
				'remarkcolor'      => $_GPC['remarkcolor'],
		    );
			if(!empty($id)) {
				pdo_update('stonefish_fighting_tmplmsg', $data, array('id' => $id));
				message('消息模板修改成功！', url('site/entry/tmplmsg', array('m' => 'stonefish_fighting')), 'success');
			}else{
				pdo_insert('stonefish_fighting_tmplmsg', $data);
				message('消息模板添加成功！', url('site/entry/tmplmsg', array('m' => 'stonefish_fighting')), 'success');
			}			
		}
        include $this->template('tmplmsgpost');
    }
	//消息模板修改
	//消息模板删除
	public function doWebTmplmsgdel() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_tmplmsg')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				pdo_delete('stonefish_fighting_tmplmsg', array('id' => $id));
				message('消息模板删除成功', referer(), 'success');
			}else{
				message('消息模板不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//消息模板删除
	//题库管理
	public function doWebQuestions() {
        global $_GPC, $_W;
		//$params = array(':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['keyword'])) {
			$where .= ' AND `question` LIKE :question';
            $params[':question'] = "%{$_GPC['keyword']}%";
        }
		if (!empty($_GPC['questiontype'])) {
			$where .= ' AND `questiontype` =:questiontype';
            $params[':questiontype'] = $_GPC['questiontype'];
        }
		$questiontype = pdo_fetchall("SELECT id,gname FROM ".tablename('stonefish_fighting_questiontype')." where uniacid=0 Or uniacid= '{$_W['uniacid']}' ORDER BY parentid, displayorder DESC, id");
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_questionbank') . "  where (uniacid=0 Or uniacid= '{$_W['uniacid']}') " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_questionbank') . " where (uniacid=0 Or uniacid= '{$_W['uniacid']}') " . $where . " order by id desc " . $limit, $params);
		foreach ($list as &$item) {
			$item['questiontype'] = pdo_fetchcolumn("SELECT gname FROM ".tablename('stonefish_fighting_questiontype')." WHERE id = '{$item['questiontype']}' ORDER BY parentid, displayorder DESC, id");
		}
        include $this->template('questions');
    }
	//题库管理
	//参与粉丝信息
	public function doWebQuestionsview() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			//题库
			$question = pdo_fetch("select * FROM " . tablename('stonefish_fighting_questionbank') . " where id = :id ORDER BY `id` DESC", array(':id' => $uid));
			$question['questiontype'] = pdo_fetchcolumn("SELECT gname FROM ".tablename('stonefish_fighting_questiontype')." WHERE id = '{$question['questiontype']}'");;
			include $this->template('questionsview');
			exit();
		}
    }
	//参与粉丝信息
	//题库修改
	public function doWebQuestionspost() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_questionbank')." where id = :id", array(':id' => $id));				
		}else{
			$item['uniacid'] = $_W['uniacid'];
		}
		$questiontype = pdo_fetchall("SELECT * FROM ".tablename('stonefish_fighting_questiontype')." WHERE uniacid = '{$_W['uniacid']}' OR uniacid = 0 ORDER BY parentid, displayorder DESC, id");
		if(empty($questiontype)){
			message('请先添加问题分类', url('site/entry/questiontype', array('m' => 'stonefish_fighting')), 'warning');
		}
		if(checksubmit('submit')) {
			if(empty($_GPC['figure'])){
				message('分值必需输入', referer(), 'error');
			}
			if(empty($_GPC['question'])){
				message('问题名称必需输入', referer(), 'error');
			}
			if(empty($_GPC['optionA']) && empty($_GPC['optionB']) && empty($_GPC['optionC']) && empty($_GPC['optionD']) && empty($_GPC['optionE']) && empty($_GPC['optionF'])){
				message('必需输入一个选项', referer(), 'error');
			}
			if(empty($_GPC['optionAtrue']) && empty($_GPC['optionBtrue']) && empty($_GPC['optionCtrue']) && empty($_GPC['optionDtrue']) && empty($_GPC['optionEtrue']) && empty($_GPC['optionFtrue'])){
				message('大侠，正确答案呢', referer(), 'error');
			}			
			$data = array(
				'uniacid'          => $_GPC['uniacid'],
				'public'           => $_GPC['public'],
				'figure'           => $_GPC['figure'],
				'question'         => $_GPC['question'],
				'optionA'          => $_GPC['optionA'],
				'optionB'          => $_GPC['optionB'],
				'optionC'          => $_GPC['optionC'],
				'optionD'          => $_GPC['optionD'],
				'optionE'          => $_GPC['optionE'],
				'optionF'          => $_GPC['optionF'],
				'answer'           => $_GPC['answer'],
				'optionAtrue'      => $_GPC['optionAtrue'],
				'optionBtrue'      => $_GPC['optionBtrue'],
				'optionCtrue'      => $_GPC['optionCtrue'],
				'optionDtrue'      => $_GPC['optionDtrue'],
				'optionEtrue'      => $_GPC['optionEtrue'],
				'optionFtrue'      => $_GPC['optionFtrue'],
				'answer'           => $_GPC['answer'],
				'questiontype'     => $_GPC['questiontype'],
		    );
			if(!empty($id)) {
				pdo_update('stonefish_fighting_questionbank', $data, array('id' => $id));
				message('问题修改成功！', url('site/entry/questions', array('m' => 'stonefish_fighting')), 'success');
			}else{
				pdo_insert('stonefish_fighting_questionbank', $data);
				message('问题添加成功！', url('site/entry/questions', array('m' => 'stonefish_fighting')), 'success');
			}			
		}
        include $this->template('questionspost');
    }
	//题库修改
	//题库删除
	public function doWebQuestionsdel() {
        global $_GPC, $_W;
        $id = intval($_GPC['id']);
		load()->func('tpl');
		if(!empty($id)) {
			$item = pdo_fetch("select * FROM ".tablename('stonefish_fighting_questionbank')." where id = :id", array(':id' => $id));
			if(!empty($item)){
				pdo_delete('stonefish_fighting_questionbank', array('id' => $id));
				message('问题删除成功', referer(), 'success');
			}else{
				message('问题不存在或已删除', referer(), 'error');
			}
		}else{
			message('系统出错', referer(), 'error');
		}
    }
	//题库删除
	//题库类型
	public function doWebQuestiontype() {
		//这个操作被定义用来呈现 管理中心导航菜单
		global $_W, $_GPC;
		checklogin();		
		$op = $_GPC['op'];
		$dos = array('category', 'postcategory', 'delcategory');
        $op = in_array($op, $dos) ? $op : 'category';
		
		if ($op == 'category') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					$update = array('displayorder' => $displayorder);
					pdo_update('stonefish_fighting_questiontype', $update, array('id' => $id));					
				}
				message('题库分类排序更新成功！', 'refresh', 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM ".tablename('stonefish_fighting_questiontype')." WHERE uniacid = '{$_W['uniacid']}' Or uniacid = 0 ORDER BY parentid, displayorder DESC, id");
			foreach ($category as $index => $row) {
				$row['total'] = $category[$index]['total'] = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_questionbank') . "  where questiontype = '{$row['id']}'");
				if (!empty($row['parentid'])){
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}				
			}
		}
		if($op == 'postcategory'){
	        $parentid = intval($_GPC['parentid']);
	        $id = intval($_GPC['id']);
			$uniacid = $_W['uniacid'];
	        if(!empty($id)) {
		        $category = pdo_fetch("SELECT * FROM ".tablename('stonefish_fighting_questiontype')." WHERE id = '$id' AND (uniacid = {$_W['uniacid']} Or uniacid =0)");
		        if(empty($category)) {
			        message('题库分类不存在或已删除', '', 'error');
	        	}
	        } else {
		        $category = array(
			        'displayorder' => 0,
					'uniacid' => $_W['uniacid'],					
		        );
	        }
	        if (!empty($parentid)) {
		        $parent = pdo_fetch("SELECT id, gname FROM ".tablename('stonefish_fighting_questiontype')." WHERE id = '$parentid'");
		        if (empty($parent)) {
			        message('抱歉，上级题库分类不存在或是已经被删除！', url('site/entry/questiontype', array('op'=>'category','m' => 'stonefish_fighting')), 'error');
		        }
	        }

	        if (checksubmit('submit')) {
		        if (empty($_GPC['gname'])) {
			        message('抱歉，请输入题库分类名称！');
		        }
		        $data = array(
			        'uniacid' => $_GPC['uniacid'],
			        'gname' => $_GPC['gname'],
			        'displayorder' => intval($_GPC['displayorder']),
			        'parentid' => intval($parentid),
					'enabled' => $_GPC['enabled'],
			        'description' => $_GPC['description'],					
		        );
		        
		        if (!empty($id)) {
			        unset($data['parentid']);
			        pdo_update('stonefish_fighting_questiontype', $data, array('id' => $id));
		        } else {
			        pdo_insert('stonefish_fighting_questiontype', $data);
			        $id = pdo_insertid();
		        }
		        message('更新题库分类成功！', url('site/entry/questiontype', array('op'=>'category','m' => 'stonefish_fighting')), 'success');
	        }
		}
		if ($op == 'delcategory') {
			$id = intval($_GPC['id']);
	        $category = pdo_fetch("SELECT * FROM ".tablename('stonefish_fighting_questiontype')." WHERE id = '$id'");
	        if (empty($category)) {
		        message('抱歉，题库分类不存在或是已经被删除！', url('site/entry/questiontype', array('op'=>'category','m' => 'stonefish_fighting')), 'error');
	        }
			pdo_delete('stonefish_fighting_questiontype', array('id' => $id, 'parentid' => $id), 'OR');
			message('题库分类删除成功！', url('site/entry/questiontype', array('op'=>'category','m' => 'stonefish_fighting')), 'success');
		}
		
		include $this->template('questiontype');
	}
	//题库类型
	//活动状态设置
    public function doWebSetshow() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $isshow = intval($_GPC['isshow']);

        if (empty($rid)) {
            message('抱歉，传递的参数错误！', '', 'error');
        }
        $temp = pdo_update('stonefish_fighting_reply', array('isshow' => $isshow), array('rid' => $rid));
		if($isshow){
			message('状态设置成功！活动已开启！', referer(), 'success');
		}else{
			message('状态设置成功！活动已关闭！', referer(), 'success');
		}
       
    }
	//活动状态设置
	//删除活动
	public function doWebDelete() {
        global $_GPC, $_W;
        $rid = intval($_GPC['rid']);
        $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
        if (empty($rule)) {
            message('抱歉，要修改的规则不存在或是已经被删除！');
        }
        if (pdo_delete('rule', array('id' => $rid))) {
            pdo_delete('rule_keyword', array('rid' => $rid));
            //删除统计相关数据
            pdo_delete('stat_rule', array('rid' => $rid));
            pdo_delete('stat_keyword', array('rid' => $rid));
            //调用模块中的删除
            $module = WeUtility::createModule($rule['module']);
            if (method_exists($module, 'ruleDeleted')) {
                $module->ruleDeleted($rid);
            }
        }
        message('活动删除成功！', referer(), 'success');
    }
	//删除活动
	//批理删除活动
	public function doWebDeleteAll() {
        global $_GPC, $_W;
        foreach ($_GPC['idArr'] as $k => $rid) {
            $rid = intval($rid);
            if ($rid == 0)
                continue;
            $rule = pdo_fetch("select id, module from " . tablename('rule') . " where id = :id and uniacid=:uniacid", array(':id' => $rid, ':uniacid' => $_W['uniacid']));
            if (empty($rule)) {
				echo json_encode(array('errno' => 1,'error' => '抱歉，要修改的规则不存在或是已经被删除！'));
				exit;
            }
            if (pdo_delete('rule', array('id' => $rid))) {
                pdo_delete('rule_keyword', array('rid' => $rid));
                //删除统计相关数据
                pdo_delete('stat_rule', array('rid' => $rid));
                pdo_delete('stat_keyword', array('rid' => $rid));
                //调用模块中的删除
                $module = WeUtility::createModule($rule['module']);
                if (method_exists($module, 'ruleDeleted')) {
                    $module->ruleDeleted($rid);
                }
            }
        }
        //message('选择中的活动删除成功！', referer(), 'success');
		echo json_encode(array('errno' => 0,'error' => '选择中的活动删除成功！'));
		exit;
    }
	//批理删除活动
	//消息通知记录
	public function doWebPosttmplmsg() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		$reply = pdo_fetch("select poweravatar from ".tablename('stonefish_fighting_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数		
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
            $where.=' and b.nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }
		if (!empty($_GPC['realname'])) {     
            $where.=' and b.realname LIKE :realname';
            $params[':realname'] = "%{$_GPC['realname']}%";
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and b.mobile LIKE :mobile';
            $params[':mobile'] = "%{$_GPC['mobile']}%";
        }		
		$total = pdo_fetchcolumn("select count(a.id) from " . tablename('stonefish_fighting_fanstmplmsg') . " as a," . tablename('stonefish_fighting_fans') . " as b where a.rid = :rid and a.uniacid=:uniacid and a.from_user=b.from_user" . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select a.tmplmsg,a.createtime,b.avatar,b.realname,b.nickname,b.mobile,c.template_name from " . tablename('stonefish_fighting_fanstmplmsg') . " as a," . tablename('stonefish_fighting_fans') . " as b," . tablename('stonefish_fighting_tmplmsg') . " as c where a.rid = :rid and a.uniacid=:uniacid and a.from_user=b.from_user and c.id=a.tmplmsgid" . $where . " order by a.id desc " . $limit, $params);
		
        include $this->template('posttmplmsg');
    }
	//消息通知记录
	//参与活动粉丝
	public function doWebFansdata() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {
            $where.=' and nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }
		if (!empty($_GPC['realname'])) {     
            $where.=' and realname LIKE :realname';
            $params[':realname'] = "%{$_GPC['realname']}%";
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and mobile LIKE :mobile';
            $params[':mobile'] = "%{$_GPC['mobile']}%";
        }				
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//中奖情况以及是否为关注会员并发送消息
		foreach ($list as &$lists) {
			$lists['fanid'] = pdo_fetchcolumn("select fanid FROM ".tablename('mc_mapping_fans') ." where openid=:openid and uniacid=:uniacid",array(":openid"=>$lists['from_user'],":uniacid"=>$_W['uniacid']));
		}
		//中奖情况以及是否为关注会员并发送消息
        include $this->template('fansdata');
    }
	//参与活动粉丝
	//参与活动粉丝状态
	public function doWebSetfansstatus() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$data = intval($_GPC['data']);
		if ($id) {
			$data = ($data==1?'0':'1');
			pdo_update("stonefish_fighting_fans", array('status' => $data), array("id" => $id));
			die(json_encode(array("result" => 1, "data" => $data)));
		}
		die(json_encode(array("result" => 0)));
	}
	//参与活动粉丝状态
	//删除参与活动粉丝
	public function doWebDeletefans() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * from ".tablename('stonefish_fighting_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
		if(empty($reply)){
			echo json_encode(array('errno' => 1,'error' => '抱歉，传递的参数错误！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if($id == 0)
                continue;
			$fans = pdo_fetch("select * from ".tablename('stonefish_fighting_fans')." where id = :id", array(':id' => $id));
            if(empty($fans)){
				echo json_encode(array('errno' => 1,'error' => '抱歉，选中的粉丝数据不存在！'));
				exit;
            }			
			//删除粉丝答题详细记录
			pdo_delete('stonefish_fighting_logs', array('from_user' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝答题详细记录
			//删除粉丝分享详细记录
			pdo_delete('stonefish_fighting_sharedata', array('fromuser' => $fans['from_user'],'rid' => $rid,'uniacid' => $_W['uniacid']));
			//删除粉丝分享详细记录
			//删除粉丝参与记录
			pdo_delete('stonefish_fighting_fans', array('id' => $id));
			//删除粉丝参与记录
			//减少参与记录
			pdo_update('stonefish_fighting_reply', array('fansnum' => $reply['fansnum']-1), array('id' => $reply['id']));
			//减少参与记录
        }
		echo json_encode(array('errno' => 0,'error' => '选中的粉丝删除成功！'));
		exit;
    }
	//删除参与活动粉丝
	//参与粉丝信息
	public function doWebUserinfo() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//兑奖资料
			$reply = pdo_fetch("select * FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			$exchange = pdo_fetch("select * FROM ".tablename("stonefish_fighting_exchange")." where rid = :rid", array(':rid' => $rid));
			$isfansname = explode(',',$exchange['isfansname']);
			//粉丝数据
			if($uid){
				$data = pdo_fetch("select * FROM ".tablename('stonefish_fighting_fans')." where id = :id", array(':id' => $uid));
			}else{
				echo '未找到指定粉丝资料';
				exit;
			}
			include $this->template('userinfo');
			exit();
		}
    }
	//参与粉丝信息
	//助力详细情况
	public function doWebSharelist() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//粉丝数据
			$data = pdo_fetch("select id, from_user  FROM " . tablename('stonefish_fighting_fans') . ' where id = :id', array(':id' => $uid));
			$share = pdo_fetchall("select * FROM " . tablename('stonefish_fighting_sharedata') . "  where rid = :rid and uniacid=:uniacid and fromuser=:fromuser ORDER BY id DESC ", array(':uniacid' => $_W['uniacid'], ':rid' => $rid, ':fromuser' => $data['from_user']));
			include $this->template('sharelist');
			exit();
		}
    }
	//助力详细情况
	//虚拟助力
	public function doWebAddxunishare() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			load()->func('tpl');
			$uid = intval($_GPC['uid']);
			$rid = intval($_GPC['rid']);
			//规则
			$reply = pdo_fetch("select * FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			//粉丝数据
			$data = pdo_fetch("select *  FROM " . tablename('stonefish_fighting_fans') . ' where id = :id', array(':id' => $uid));
			include $this->template('addxunishare');
			exit();
		}
    }
	public function doWebSavexunishare() {
        global $_GPC, $_W;
		$uid = intval($_GPC['uid']);
		$rid = intval($_GPC['rid']);
		$viewnum = intval($_GPC['viewnum']);
		$point = intval($_GPC['point']);
		if(!$point){
		    message('助力额必需填写', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_fighting')), 'error');
		}
		if(!$rid){
		    message('系统出错', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_fighting')), 'error');
		}
		if($uid) {
		    //规则
			$reply = pdo_fetch("select * FROM " . tablename('stonefish_fighting_reply') . " where rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
			//粉丝数据
			$data = pdo_fetch("select * FROM " . tablename('stonefish_fighting_fans') . ' where id = :id', array(':id' => $uid));
			//添加中奖记录
            $insert = array(
                'uniacid' => $_W['uniacid'],
                'rid' => $rid,
                'from_user' => '系统虚拟者',
                'fromuser' => $data['from_user'],
                'avatar' => $_GPC['avatar'],
                'nickname' => $_GPC['nickname'],
				'visitorsip' => CLIENT_IP,
                'viewnum' => $viewnum,
				'point' => $point,
                'visitorstime' => time()
            );
            pdo_insert('stonefish_fighting_sharedata', $insert);
			//添加中奖记录
            //设置此粉丝为虚拟中奖者
            pdo_update('stonefish_fighting_fans', array('sharepoint' => $data['sharepoint'] + $point,'xuni' => 1), array('id' => $data['id']));
			//设置此粉丝为虚拟中奖者
			message('添加虚拟助力量成功', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_fighting')));
		}else{
			message('未找到指定用户', url('site/entry/fansdata',array('rid' => $rid, 'm' => 'stonefish_fighting')), 'error');
		}       
    }
	//虚拟助力	
	//参与活动粉丝分享数据
	public function doWebSharedata() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['nickname'])) {     
            $where.=' and nickname=:nickname';
            $params[':nickname'] = $_GPC['nickname'];
        }
		if (!empty($_GPC['fromuser'])) {     
            $where.=' and fromuser=:fromuser';
            $params[':fromuser'] = $_GPC['fromuser'];
        }
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_sharedata') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_sharedata') . " where rid = :rid and uniacid=:uniacid " . $where . " order by id desc " . $limit, $params);
		//分享人
		foreach ($list as &$lists) {
			$fans = pdo_fetch("select avatar,nickname,realname from " . tablename('stonefish_fighting_fans') . "  where rid = :rid and from_user=:from_user", array(':rid' => $rid,':from_user' => $lists['fromuser']));
			$lists['favatar'] =$fans['avatar'];
			$lists['fnickname'] =$fans['nickname'];
			$lists['frealname'] =$fans['realname'];
		}
		//分享人
        include $this->template('sharedata');
    }
	//参与活动粉丝分享数据
	//删除参与活动粉丝分享数据
	public function doWebDeletesharedata() {
        global $_GPC, $_W;
		$rid = intval($_GPC['rid']);
		$reply = pdo_fetch("select * from ".tablename('stonefish_fighting_reply')." where rid = :rid and uniacid=:uniacid", array(':rid' => $rid, ':uniacid' => $_W['uniacid']));
        if(empty($reply)){
			echo json_encode(array('errno' => 1,'error' => '抱歉，传递的参数错误！'));
			exit;
        }
        foreach ($_GPC['idArr'] as $k => $id) {
            $id = intval($id);
            if($id == 0)
                continue;
			$sharedata = pdo_fetch("select id,fromuser from ".tablename('stonefish_fighting_sharedata')." where id = :id", array(':id' => $id));
            if(empty($sharedata)){
				echo json_encode(array('errno' => 1,'error' => '抱歉，选中的数据不存在！'));
				exit;
            }
			$fans = pdo_fetch("select * from " . tablename('stonefish_fighting_fans') . " where rid = :rid and uniacid=:uniacid and from_user=:from_user", array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':from_user' => $sharedata['fromuser']));
			//减少参与粉丝分享助力
			pdo_update('stonefish_fighting_fans', array('sharenum' => $fans['sharenum']-1), array('id' => $fans['id']));
			//减少参与粉丝分享助力			
			//删除粉丝分享记录
			pdo_delete('stonefish_fighting_sharedata', array('id' => $sharedata['id']));
			//删除粉丝分享记录
        }
		echo json_encode(array('errno' => 0,'error' => '选中的分享数据删除成功！'));
		exit;
    }
	//删除参与活动粉丝分享数据
	//参与活动粉丝答题记录
	public function doWebAnswerdata() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		if (!empty($_GPC['from_user'])) {     
            $where.=' and a.from_user=:from_user';
            $params[':from_user'] = $_GPC['from_user'];
        }
		if (!empty($_GPC['nickname'])) {
            $where.=' and b.nickname LIKE :nickname';
            $params[':nickname'] = "%{$_GPC['nickname']}%";
        }
		if (!empty($_GPC['realname'])) {     
            $where.=' and b.realname LIKE :realname';
            $params[':realname'] = "%{$_GPC['realname']}%";
        }
		if (!empty($_GPC['mobile'])) {     
            $where.=' and b.mobile LIKE :mobile';
            $params[':mobile'] = "%{$_GPC['mobile']}%";
        }
		$total = pdo_fetchcolumn("select count(*) from ".tablename('stonefish_fighting_logs')." as a JOIN ".tablename('stonefish_fighting_fans')." as b ON (a.from_user = b.from_user)  where a.rid = :rid and a.uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select a.*,b.avatar,b.nickname,b.realname,b.mobile from ".tablename('stonefish_fighting_logs')." as a JOIN ".tablename('stonefish_fighting_fans')." as b ON (a.from_user = b.from_user)  where a.rid = :rid and a.uniacid=:uniacid " . $where . " order by a.id desc " . $limit, $params);
        include $this->template('answerdata');
    }
	//参与活动粉丝答题记录
	//答题详情
	public function doWebLogs() {
        global $_GPC, $_W;
		if($_W['isajax']) {
			load()->func('tpl');
			$id = intval($_GPC['id']);
			//答题记录
			$logs = pdo_fetch("select *  FROM " . tablename('stonefish_fighting_logs') . ' where id = :id', array(':id' => $id));
			$questionids = explode(',',$logs['questionids']);
			//问题记录
			$questionbank = pdo_fetchall("select question,questiontype FROM ".tablename('stonefish_fighting_questionbank')." where id in (".$logs['questionid'].") order by find_in_set(id,'".$logs['questionid']."')");
			foreach($questionbank as $index=>$question){
				$questionbank[$index]['gname'] = pdo_fetchcolumn("select gname from " . tablename('stonefish_fighting_questiontype') . "  where id = :id", array(':id' => $question['questiontype']));
			}
			include $this->template('logs');
			exit();
		}
    }
	//答题详情
	//参与活动粉丝排行榜
	public function doWebRankdata() {
        global $_GPC, $_W;
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
		$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid']);
		//导出标题以及参数设置
		if($_GPC['rank']=='sharenum'){
		    $statustitle = '分享值';
			$order = 'sharenum';
		}
		if($_GPC['rank']=='day'){
		    $statustitle = '今日';
			$order = 'day_credit';
			$nowtime = strtotime(date('Y-m-d'));
			$where = ' and last_time>=:last_time';
			$params = array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':last_time' => $nowtime);
		}
		if($_GPC['rank']=='rank'  || $_GPC['rank']==''){
		    $statustitle = '总';
			$order = 'last_credit';
		}
		if(empty($_GPC['page'])){
			$_GPC['page']=1;
		}
		$total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_fans') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_fans') . " where rid = :rid and uniacid=:uniacid " . $where . " order by ".$order." desc,id asc " . $limit, $params);
        include $this->template('rankdata');
    }
	//参与活动粉丝排行榜
	//参与活动粉丝每日排行榜
	public function doWebRankdaydata() {
        global $_GPC, $_W;
		load()->func('tpl');
		$rid = $_GPC['rid'];
		$rid = empty($rid) ? $_GPC['id'] : $rid;
		//查询do参数
		if(empty($_GPC['do'])){
			$_GPC['do'] = pdo_fetchcolumn("select do from " . tablename('modules_bindings') . "  where eid = :eid and module=:module", array(':eid' => $_GPC['eid'], ':module' => 'stonefish_fighting'));
		}
		//查询do参数
		$scroll = intval($_GPC['scroll']);
		$st = $_GPC['datelimit']['start'] ? strtotime($_GPC['datelimit']['start']) : strtotime('-1day');
	    $et = $_GPC['datelimit']['end'] ? strtotime($_GPC['datelimit']['end']) : strtotime(date('Y-m-d'));
		if(empty($_GPC['datelimit']['start']) && $st!=time()){
			$st=time();
		}
		if(empty($_GPC['page'])){
			$_GPC['page']=1;
		}
	    $starttime = min($st, $et);
	    $endtime = max($st, $et);
	    $endtime += 86399;
        $order = 'day_credit';
        $where = ' and createtime>=:last_time and createtime<:daytime';
        $params = array(':rid' => $rid, ':uniacid' => $_W['uniacid'], ':last_time' => $starttime, ':daytime' => $endtime);
        $total = pdo_fetchcolumn("select count(id) from " . tablename('stonefish_fighting_logs') . "  where rid = :rid and uniacid=:uniacid " . $where . "", $params);
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $pager = pagination($total, $pindex, $psize);
        $start = ($pindex - 1) * $psize;
        $limit .= " LIMIT {$start},{$psize}";
        $list = pdo_fetchall("select * from " . tablename('stonefish_fighting_logs') . " where rid = :rid and uniacid=:uniacid " . $where . " order by ".$order." desc,id asc " . $limit, $params);
		foreach ($list as &$lists) {
			$fans = pdo_fetch("select avatar,nickname,realname,mobile,share_num,sharenum,totalnum,last_credit from " . tablename('stonefish_fighting_fans') . "  where rid = :rid and uniacid=:uniacid and from_user=:from_user", array(':rid' => $rid, ':uniacid' => $_W['uniacid'] ,':from_user' => $lists['from_user']));
			$lists['avatar'] =$fans['avatar'];
			$lists['nickname'] =$fans['nickname'];
			$lists['realname'] =$fans['realname'];
			$lists['mobile'] =$fans['mobile'];
			$lists['share_num'] =$fans['share_num'];
			$lists['sharenum'] =$fans['sharenum'];
			$lists['totalnum'] =$fans['totalnum'];
			$lists['last_credit'] =$fans['last_credit'];
		}
        include $this->template('rankdaydata');
    }
	//参与活动粉丝每日排行榜
	//导出数据
	public function doWebDownload() {
        require_once 'download.php';
    }
	//导出数据
	//借用ＪＳ分享
	function getSignPackage($appId,$appSecret) {
		global $_W;
        $jsapiTicket = $this->getJsApiTicket($_W['uniacid'],$appId,$appSecret);
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string1 = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string1);
		$signPackage = array(
			"appId"		=> $appId,
			"nonceStr"	=> $nonceStr,
			"timestamp" => "$timestamp",
			"signature" => $signature,
		);
		
		if(DEVELOPMENT) {
			$signPackage['url'] = $url;
			$signPackage['string1'] = $string1;
			$signPackage['name'] = $_W['account']['name'];
		}        
        return $signPackage;
    }

    function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    function getJsApiTicket($uniacid,$appId,$appSecret) {
        load()->func('cache');
        $api = cache_load("stonefish_fighting.api_share.json::".$uniacid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$appSecret){
            $new = true;
        }      
        $data = cache_load("stonefish_fighting.jsapi_ticket.json::".$uniacid, true);
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $accessToken = $this->getAccessToken($uniacid,$appId,$appSecret);       
            $url = "http://api.weixin.qq.com/cgi-bin/ticket/getticket?type=1&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data['expire_time'] = time() + 7000;
                $data['jsapi_ticket'] = $ticket;
                cache_write("stonefish_fighting.jsapi_ticket.json::".$uniacid, iserializer($data));
                cache_write("stonefish_fighting.api_share.json::".$uniacid, iserializer(array("appid"=>$appId,"appsecret"=>$appSecret)));
            }
        } else {
            $ticket = $data['jsapi_ticket'];
        }
        return $ticket;
    }

    function getAccessToken($uniacid,$appId,$appSecret) {
        load()->func('cache');
        $api = cache_load("stonefish_fighting.api_share.json::".$uniacid, true);
        $new = false;
        if(empty($api['appid']) || $api['appid']!==$appId){
            $new = true;
        }
        if(empty($api['appsecret']) || $api['appsecret']!==$appSecret){
            $new = true;
        }
        $data = cache_load("stonefish_fighting.access_token.json::".$uniacid, true);     
        if (empty($data['expire_time']) || $data['expire_time'] < time() || $new) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data['expire_time'] = time() + 7000;
                $data['access_token'] = $access_token;
                cache_write("stonefish_fighting.access_token.json::".$uniacid, iserializer($data));
                cache_write("stonefish_fighting.api_share.json::".$uniacid, iserializer(array("appid"=>$appId,"appsecret"=>$appSecret)));
            }
        } else {
            $access_token = $data['access_token'];
        }
        return $access_token;
    }
	function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }
	//借用ＪＳ分享
}