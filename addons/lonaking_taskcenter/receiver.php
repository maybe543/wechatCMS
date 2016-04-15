<?php
/**
 * 猪八戒推广中心模块订阅器
 *
 * @author lonaking
 * @url http://bbs.we7.cc/thread-8992-1-1.html
 */
defined('IN_IA') or exit('Access Denied');
require_once 'utils/TableResource.class.php';
class Lonaking_taskcenterModuleReceiver extends WeModuleReceiver {
	public function receive() {
		global $_W;
		$openid = $this->message['from'];
		$fans = pdo_fetch("SELECT fanid,openid FROM ".tablename("mc_mapping_fans")." WHERE openid = :openid",array(":openid"=>$this->message['from']));
		$config = $this->module['config'];
		//a. 扫描本模块的二维码进行关注
		if($this->message['event'] == 'subscribe' && !empty($this->message['eventkey'])){
			$scene = $this->message['scene'];
			$parent_user = pdo_fetch("SELECT ".TableResource::$table['user']['columns']." FROM ".tablename(TableResource::$table['user']['name'])." WHERE scene_id =:scene_id AND uniacid =:uniacid" ,array(':scene_id'=>$scene,':uniacid'=>$_W['uniacid']));
			//获取场景值  场景值为每个人推广模块share表id
			$pid = $parent_user['id'];
			//将数据存储到invite表中
			if(!empty($pid)){
				$invite_id = $pid;
				$invite_log = array(
					'uniacid' => $_W['uniacid'],
					'fanid' => $fans['fanid'],
					'openid' => $_W['openid'],
					'invite_id' => $invite_id,
				);
				pdo_insert(TableResource::$table['invite']['name'],$invite_log);
				//2. 更新推广人信息 关注人数 + 积分
				pdo_query("UPDATE ".tablename(TableResource::$table['user']['name']). " SET follow_times = follow_times+1,score = score+". $config['follow_score'] ." WHERE id = :id",array(':id'=>$invite_id));
				//3. 判断是否同步微擎积分
				if($config['score_we7']){//
					load()->model('mc');
					$uid = mc_openid2uid($parent_user['openid']);
					//'credit1','credit2'  1=> 积分 2=>金额
					mc_credit_update($uid,'credit1',$config['follow_score']);
				}
				//发给推广人关注消息
				$this->log('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');
				$this->sendInviteFollowTplNotice($config['follow_score'],$parent_user['openid'],$_W['uniaccount']['uniacid']);
				$this->log('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');
			}
		}
		//b. 取消关注
		if($this->message['event'] == 'unsubscribe'){
			$invite_info = pdo_fetch("SELECT ".TableResource::$table['invite']['columns']." FROM ".tablename(TableResource::$table['invite']['name'])." WHERE uniacid =:uniacid AND openid =:openid",array(':uniacid'=>$_W['uniacid'], ':openid' => $openid) );
			$current_user = pdo_fetch("SELECT ".TableResource::$table['user']['columns'] ." FROM ".tablename(TableResource::$table['user']['name']) ." WHERE uniacid =:uniacid AND openid =:openid",array(':uniacid'=>$_W['uniacid'], ':openid' => $openid) );
			if(empty($invite_info['invite_id'])){//this people is a alone man ,no one invited him
				if(!empty($current_user['id'])){
					pdo_delete(TableResource::$table['user']['name'],array('id'=>$current_user['id']));
				}
			}else{
				$parent_user = pdo_fetch("SELECT ".TableResource::$table['user']['columns']." FROM ".tablename(TableResource::$table['user']['name'])." WHERE id =:id" ,array(':id'=>$invite_info['invite_id']));
				if(!empty($current_user['id'])){//judge it is a share man ?
					$invite_id = $current_user['pid'];//推广人id
					//更新推广人的表
					if(!empty($invite_id)){
						pdo_query("UPDATE ".tablename(TableResource::$table['user']['name']). " SET unfollow_times = unfollow_times+1,score = score-". $config['unfollow_score'] ." WHERE id =:id",array(':id'=>$invite_id) );
						pdo_delete(TableResource::$table['invite']['name'],array('uniacid'=>$_W['uniacid'], 'openid' => $openid));
					}
					pdo_delete(TableResource::$table['user']['name'],array('id'=>$current_user['id']));
				}else{
					$invite_id = $invite_info['invite_id'];
					pdo_query("UPDATE ".tablename(TableResource::$table['user']['name']). " SET unfollow_times = unfollow_times+1,score = score-". $config['unfollow_score'] ." WHERE id =:id",array(':id'=>$invite_id) );
					pdo_delete(TableResource::$table['invite']['name'],array('uniacid'=>$_W['uniacid'], 'openid' => $openid));
				}
				//3. 判断是否同步微擎积分
				if($config['score_we7']){//
					load()->model('mc');
					$uid = mc_openid2uid($parent_user['openid']);
					//'credit1','credit2'  1=> 积分 2=>金额
					mc_credit_update($uid,'credit1',$config['follow_score']*-1);
				}
			}
		}
	}
	/**
	 * 邀请关注积分奖励通知
	 */
	private function sendInviteFollowTplNotice($score,$openid,$uniacid){
		$config = pdo_fetch("SELECT * FROM ".tablename('lonaking_supertask_tpl_template_config')." WHERE uniacid=:uniacid",array(':uniacid'=>$uniacid));
		$template_id = $config['invite_score_notice'];
		$postData = array(
			'first' => array(
				'value' => '有用户通过您的二维码关注,系统已经将奖励积分发放到您的账户，请注意查看',
				'color' => '#FF683F'
			),
			'keyword1' => array(
				'value' => $score,
				'color' => '#FF683F'
			),
			'keyword2' => array(
				'value' => date('m月d日 H:i',time()),
				'color' => '#FF683F'
			),
			'remark' => array(
				'value' => '',
				'color' => '#FF683F'
			)
		);
		$sendArray = array(
			'openid' => $openid,
			'template_id' =>$template_id,
			'postData' => $postData,
		);
		$this->log($sendArray);
		$response = $this->sendTplNotice($openid, $template_id, $postData);
		$this->log($response);
	}

	public function sendTplNotice($touser, $template_id, $postdata, $url = '', $topcolor = '#FF683F') {
//		if(empty($this->account['secret']) || empty($this->account['key']) || $this->account['level'] != 4) {
//			return error(-1, '你的公众号没有发送模板消息的权限');
//		}
		if(empty($touser)) {
			$this->log('参数错误,粉丝openid不能为空');
			return error(-1, '参数错误,粉丝openid不能为空');
		}
		if(empty($template_id)) {
			$this->log('参数错误,模板标示不能为空');
			return error(-1, '参数错误,模板标示不能为空');
		}
		if(empty($postdata) || !is_array($postdata)) {
			$this->log('参数错误,请根据模板规则完善消息内容');
			return error(-1, '参数错误,请根据模板规则完善消息内容');
		}

		$data = array();
		$data['touser'] = $touser;
		$data['template_id'] = trim($template_id);
		$data['url'] = trim($url);
		$data['topcolor'] = trim($topcolor);
		$data['data'] = $postdata;
		$data = json_encode($data);
		global $_W;
		$access_token = $_W['account']['access_token']['token'];
		$post_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
		$this->log('postUrl组成完毕'.$post_url);
		$response = $this->http_request($post_url, $data);
		if(is_error($response)) {
			$this->log("访问公众平台接口失败, 错误: {$response['message']}");
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = json_decode($response['content'], true);
		if(empty($result)) {
			$this->log("接口调用失败, 元数据: {$response['meta']}");
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			$this->log("访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
		}
		return true;
	}

	/**
	 * 日志方法
	 * @param $content
	 */
	public function log($content){
		$c = '';
		if (is_array($content)) {
			foreach ($content as $key => $value) {
				$c .= "$key : $value \r\n";
			}
		} else {
			$c = "this is str" . $content;
		}
		file_put_contents(dirname(__FILE__) . "/lonaking_taskcenter_receiver.txt", $c, FILE_APPEND);
	}

	/**
	 * 发送post请求
	 * @param $url
	 * @param null $data
	 * @return mixed
	 */
	private function http_request($url,$data=null){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST, 1);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
		$out_put = curl_exec($curl);
		curl_close($curl);
		return $out_put;
	}

	function ihttp_request($url, $post = '', $extra = array(), $timeout = 60) {
		$urlset = parse_url($url);
		if(empty($urlset['path'])) {
			$urlset['path'] = '/';
		}
		if(!empty($urlset['query'])) {
			$urlset['query'] = "?{$urlset['query']}";
		}
		if(empty($urlset['port'])) {
			$urlset['port'] = $urlset['scheme'] == 'https' ? '443' : '80';
		}
		if (strexists($url, 'https://') && !extension_loaded('openssl')) {
			if (!extension_loaded("openssl")) {
				message('请开启您PHP环境的openssl');
			}
		}
		if(function_exists('curl_init') && function_exists('curl_exec')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlset['scheme']. '://' .$urlset['host'].($urlset['port'] == '80' ? '' : ':'.$urlset['port']).$urlset['path'].$urlset['query']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			if($post) {
				if (is_array($post)) {
					$filepost = false;
					foreach ($post as $name => $value) {
						if (substr($value, 0, 1) == '@') {
							$filepost = true;
							break;
						}
					}
					if (!$filepost) {
						$post = http_build_query($post);
					}
				}
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSLVERSION, 1);
			if (defined('CURL_SSLVERSION_TLSv1')) {
				curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
			}
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
			if (!empty($extra) && is_array($extra)) {
				$headers = array();
				foreach ($extra as $opt => $value) {
					if (strexists($opt, 'CURLOPT_')) {
						curl_setopt($ch, constant($opt), $value);
					} elseif (is_numeric($opt)) {
						curl_setopt($ch, $opt, $value);
					} else {
						$headers[] = "{$opt}: {$value}";
					}
				}
				if(!empty($headers)) {
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				}
			}
			$data = curl_exec($ch);
			$status = curl_getinfo($ch);
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			if($errno || empty($data)) {
				return error(1, $error);
			} else {
				return ihttp_response_parse($data);
			}
		}
		$method = empty($post) ? 'GET' : 'POST';
		$fdata = "{$method} {$urlset['path']}{$urlset['query']} HTTP/1.1\r\n";
		$fdata .= "Host: {$urlset['host']}\r\n";
		if(function_exists('gzdecode')) {
			$fdata .= "Accept-Encoding: gzip, deflate\r\n";
		}
		$fdata .= "Connection: close\r\n";
		if (!empty($extra) && is_array($extra)) {
			foreach ($extra as $opt => $value) {
				if (!strexists($opt, 'CURLOPT_')) {
					$fdata .= "{$opt}: {$value}\r\n";
				}
			}
		}
		$body = '';
		if ($post) {
			if (is_array($post)) {
				$body = http_build_query($post);
			} else {
				$body = urlencode($post);
			}
			$fdata .= 'Content-Length: ' . strlen($body) . "\r\n\r\n{$body}";
		} else {
			$fdata .= "\r\n";
		}
		if($urlset['scheme'] == 'https') {
			$fp = fsockopen('ssl://' . $urlset['host'], $urlset['port'], $errno, $error);
		} else {
			$fp = fsockopen($urlset['host'], $urlset['port'], $errno, $error);
		}
		stream_set_blocking($fp, true);
		stream_set_timeout($fp, $timeout);
		if (!$fp) {
			return error(1, $error);
		} else {
			fwrite($fp, $fdata);
			$content = '';
			while (!feof($fp))
				$content .= fgets($fp, 512);
			fclose($fp);
			return ihttp_response_parse($content, true);
		}
	}


	function ihttp_response_parse($data, $chunked = false) {
		$rlt = array();
		$pos = strpos($data, "\r\n\r\n");
		$split1[0] = substr($data, 0, $pos);
		$split1[1] = substr($data, $pos + 4, strlen($data));

		$split2 = explode("\r\n", $split1[0], 2);
		preg_match('/^(\S+) (\S+) (\S+)$/', $split2[0], $matches);
		$rlt['code'] = $matches[2];
		$rlt['status'] = $matches[3];
		$rlt['responseline'] = $split2[0];
		$header = explode("\r\n", $split2[1]);
		$isgzip = false;
		$ischunk = false;
		foreach ($header as $v) {
			$row = explode(':', $v);
			$key = trim($row[0]);
			$value = trim($row[1]);
			if (is_array($rlt['headers'][$key])) {
				$rlt['headers'][$key][] = $value;
			} elseif (!empty($rlt['headers'][$key])) {
				$temp = $rlt['headers'][$key];
				unset($rlt['headers'][$key]);
				$rlt['headers'][$key][] = $temp;
				$rlt['headers'][$key][] = $value;
			} else {
				$rlt['headers'][$key] = $value;
			}
			if(!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
				$isgzip = true;
			}
			if(!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
				$ischunk = true;
			}
		}
		if($chunked && $ischunk) {
			$rlt['content'] = ihttp_response_parse_unchunk($split1[1]);
		} else {
			$rlt['content'] = $split1[1];
		}
		if($isgzip && function_exists('gzdecode')) {
			$rlt['content'] = gzdecode($rlt['content']);
		}

		$rlt['meta'] = $data;
		if($rlt['code'] == '100') {
			return ihttp_response_parse($rlt['content']);
		}
		return $rlt;
	}

	function ihttp_response_parse_unchunk($str = null) {
		if(!is_string($str) or strlen($str) < 1) {
			return false;
		}
		$eol = "\r\n";
		$add = strlen($eol);
		$tmp = $str;
		$str = '';
		do {
			$tmp = ltrim($tmp);
			$pos = strpos($tmp, $eol);
			if($pos === false) {
				return false;
			}
			$len = hexdec(substr($tmp, 0, $pos));
			if(!is_numeric($len) or $len < 0) {
				return false;
			}
			$str .= substr($tmp, ($pos + $add), $len);
			$tmp  = substr($tmp, ($len + $pos + $add));
			$check = trim($tmp);
		} while(!empty($check));
		unset($tmp);
		return $str;
	}


	function ihttp_get($url) {
		return ihttp_request($url);
	}


	function ihttp_post($url, $data) {
		$headers = array('Content-Type' => 'application/x-www-form-urlencoded');
		return ihttp_request($url, $data, $headers);
	}
}