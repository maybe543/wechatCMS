<?php
/**
 * 嘿修4S模块微站定义
 *
 * @author 史中营 QQ:214983937
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once "jssdk.php";
class Amouse_valuationModuleSite extends WeModuleSite {

	//后台管理程序 web文件夹下
	public function __web($f_name) {
		global $_W, $_GPC;
		checklogin();
		$weid= $_W['uniacid'];
		//每个页面都要用的公共信息，今后可以考虑是否要运用到缓存
		include_once 'web/'.strtolower(substr($f_name, 5)).'.php';
	}
	
     //以旧换新
	public function doMobileIndex() {
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        $fromuser = $_W['fans']['from_user'];
        $setting= $this->get_sysset($weid);
        $models= pdo_fetchall("SELECT * FROM ".tablename('amouse_valuation_mobile_model')." WHERE `weid` = :weid  ORDER BY `id` DESC", array(':weid' => $weid));
        $oauth_openid="amouse_house_zombie_".$_W['uniacid'];
        if (empty($_COOKIE[$oauth_openid])) {
            if(!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret']) ) {
                $this->checkCookie();
            }
        }
        $url = $_W['siteroot']."app/".substr($this->createMobileUrl('index',array(),true),2);
        include $this->template('oldtonew/index');
	}


    public function doMobileV() {
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        $setting= $this->get_sysset($weid);
        $moid =  $_GPC['moid'] ;
        $list= pdo_fetchall("SELECT * FROM ".tablename('amouse_valuation_mobile_version')." WHERE `weid` = :weid and moid=:moid ORDER BY `id` DESC", array(':weid'=>$weid,':moid'=>$moid));
        include $this->template('oldtonew/version');
    }


    public function doMobilePrice() {
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        $vid =  $_GPC['vid'] ;
        $moid =  $_GPC['moid'] ;
        $model= pdo_fetch("SELECT * FROM ".tablename('amouse_valuation_mobile_model')." WHERE `weid` = :weid and id = :mid", array(':weid' => $weid,':mid'=>$moid ));

        $version= pdo_fetch("SELECT * FROM ".tablename('amouse_valuation_mobile_version')." WHERE `weid` = :weid and id = :vid", array(':weid' => $weid,':vid'=>$vid ));

        $parameters= pdo_fetchall(" SELECT * FROM " . tablename('amouse_valuation_mobile_parameter') . " WHERE `vid`=".$vid);

        $url=$_W['siteroot']."app/".substr($this->createMobileUrl('price',array('versionid'=>$vid,'modelid'=>$modelid),true),2);

        include $this->template('oldtonew/price');
    }


	private function checkIsWeixin(){
		$user_agent= $_SERVER['HTTP_USER_AGENT'];
		if(strpos($user_agent, 'MicroMessenger') === false) {
			echo "本页面仅支持微信访问!非微信浏览器禁止浏览!";
			exit;
		}
	}

	private function CheckCookie() {
		global $_W,$_GPC;
		$weid=$_W['uniacid'];
		$id=$_GPC['id'];
		$setting= $this->get_sysset($weid);
		$oauth_openid= "zombie_valuation_".$weid;
		if(empty($_COOKIE[$oauth_openid])) {
			$appid= $_W['account']['key'];
			$secret= $_W['account']['secret'];
			//是否为高级号
			$serverapp= $_W['account']['level'];
			if($serverapp != 2) { 
				if(!empty($setting) && $setting['isoauth'] == '0') {  
					if(!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret'])) { // 判断是否是借用设置
						$appid= $setting['appid'];
						$secret= $setting['appsecret'];
					}
				}
			}
			//借用的
			$url = $_W['siteroot'].$this->createMobileUrl('userinfo', array('id'=>$id));
			$oauth2_code = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";				
			header("location:$oauth2_code");
			exit;
		}
	}

	
	public function doMobileUserinfo() {
		global $_GPC, $_W;
		$weid= $_W['uniacid']; //当前公众号ID
		$id= $_GPC['id'];
		//用户不授权返回提示说明
		if($_GPC['code'] == "authdeny") {
			$url = $_W['siteroot'].$this->createMobileUrl('index', array());
			header("location:$url");
			exit('authdeny');
		}
		//高级接口取未关注用户Openid
		if(isset($_GPC['code'])) {
			//第二步：获得到了OpenID
			$appid= $_W['account']['key'];
			$secret= $_W['account']['secret'];
			$serverapp= $_W['account']['level'];
			if($serverapp != 2) {
				$setting= $this->get_sysset($weid);
				if(!empty($setting) && !empty($setting['appid']) && !empty($setting['appsecret'])) { // 判断是否是借用设置
					$appid= $setting['appid'];
					$secret= $setting['appsecret'];
				} 
				if(empty($appid) || empty($secret)) {
					return;
				}
			}
			$state= $_GPC['state'];
			//1为关注用户, 0为未关注用户
			$rid= $_GPC['id'];
			//查询活动时间
			$code= $_GPC['code'];
			$oauth2_code= "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
			$content= ihttp_get($oauth2_code);
			$token= @ json_decode($content['content'], true);
			if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['openid'])) {
				echo '<h1>获取微信公众号授权'.$code.'失败[无法取得token以及openid], 请稍后重试！ 公众平台返回原始数据为: <br />'.$content['meta'].'<h1>';
				exit;
			}
			$from_user= $token['openid'];
			//再次查询是否为关注用户
			$profile= fans_search($from_user, array('follow'));
			//关注用户直接获取信息	
			if($profile['follow'] == 1) {
				$state= 1;
			} else {
				//未关注用户跳转到授权页
				$url= $_W['siteroot'].$this->createMobileUrl('userinfo',array(":id"=>$id));
				$oauth2_code= "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".urlencode($url)."&response_type=code&scope=snsapi_userinfo&state=0#wechat_redirect";
				header("location:$oauth2_code");
			}
			//未关注用户和关注用户取全局access_token值的方式不一样
			if($state == 1) {
				$oauth2_url= "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."";
				$content= ihttp_get($oauth2_url);
				$token_all= @ json_decode($content['content'], true);
				if(empty($token_all) || !is_array($token_all) || empty($token_all['access_token'])) {
					echo '<h1>获取微信公众号授权失败[无法取得access_token], 请稍后重试！ 公众平台返回原始数据为: <br />'.$content['meta'].'<h1>';
					exit;
				}
				$access_token= $token_all['access_token'];
				$oauth2_url= "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			} else {
				$access_token= $token['access_token'];
				$oauth2_url= "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$from_user."&lang=zh_CN";
			}

			//使用全局ACCESS_TOKEN获取OpenID的详细信息			
			$content= ihttp_get($oauth2_url);
			$info= @ json_decode($content['content'], true);
			if(empty($info) || !is_array($info) || empty($info['openid']) || empty($info['nickname'])) {
				echo '<h1>获取微信公众号授权失败[无法取得info], 请稍后重试！<h1>';
				exit;
			}
			 
			if($serverapp == 2) { //普通号
				$row= array('weid' => $_W['weid'], 'nickname' => $info["nickname"], 'realname' => $info["nickname"], 'gender' => $info['sex'], 'from_user' => $info['openid']);
				if(!empty($info["country"])) {
					$row['country']= $info["country"];
				}
				if(!empty($info["province"])) {
					$row['province']= $info["province"];
				}
				if(!empty($info["city"])) {
					$row['city']= $info["city"];
				}
				fans_update($_W['fans']['from_user'], $row);
				pdo_update('fans', array('avatar' => $info["headimgurl"]), array('from_user' => $_W['fans']['from_user']));
			}

			if($serverapp != 2 && !(empty($_W['fans']['from_user']))) { //普通号
				$row= array('nickname' => $info["nickname"], 'realname' => $info["nickname"], 'gender' => $info['sex']);
				if(!empty($info["country"])) {
					$row['country']= $info["country"];
				}
				if(!empty($info["province"])) {
					$row['province']= $info["province"];
				}
				if(!empty($info["city"])) {
					$row['city']= $info["city"];
				}
				fans_update($_W['fans']['from_user'], $row);
				pdo_update('fans', array('avatar' => $info["headimgurl"]), array('from_user' => $_W['fans']['from_user']));
			}
			$oauth_openid= "zombie_valuation_".$_W['weid'];
			setcookie($oauth_openid, $info['openid'], time() + 3600 * 240);
			$url=$this->createMobileUrl('index',array('id'=>$id));
			header("location:$url");
			exit;
		} else {
			echo '<h1>网页授权域名设置出错!</h1>';
			exit;
		}
	}


	public function get_sysset($weid=0) {
		global $_GPC, $_W;
		return pdo_fetch("SELECT * FROM ".tablename('oldtonew_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
	}
	

	//手机机型管理
	public function doWebModel() {
		$this->__web(__FUNCTION__);
	}
	//手机型号管理
	public function doWebVersion() {
		$this->__web(__FUNCTION__);
	}

    public function doWebSysset() {
        global $_W, $_GPC;
        $weid= $_W['uniacid'];
        load()->func('tpl');
        $set= $this->get_sysset($weid);
        if(checksubmit('submit')) {
            $data= array(
                'weid' => $weid,
                'guanzhuUrl'=>$_GPC['guanzhuUrl'],
                'logo'=>$_GPC['logo'],
                'copyright'=>$_GPC['copyright'],
                'appid'=>$_GPC['appid'] ,
                'appsecret'=>$_GPC['appsecret'],
                'appid_share' => $_GPC['appid_share'],
                'appsecret_share' => $_GPC['appsecret_share']
            );

            if(!empty($set)) {
                pdo_update('oldtonew_sysset', $data, array('id' => $set['id']));
            } else {
                pdo_insert('oldtonew_sysset', $data);
            }
            message('更新系统设置成功！', 'refresh');
        }
        include $this->template('web/sysset');
    }

}