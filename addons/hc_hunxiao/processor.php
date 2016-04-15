<?php
/*
 * 分销自定义模块
 */

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/hc_hunxiao/responser.php';

class hc_hunxiaoModuleProcessor extends WeModuleProcessor {
	
	public function respond() {
       	global $_W;

		$uniacid = $_W['uniacid'];
		$message = $this->message;
		$openid = $message['from'];
		
		$member = pdo_fetch("select * from ".tablename('hc_hunxiao_member')." where weid = ".$uniacid." and from_user = '".$openid."'");
		$id = $member['id'];
		if(!intval($id)){
			return $this->respText('您还未申请成为分销员，请先申请后再来获取专属名片！');
		}
		$resp = new QRResponser();
		if($member['ischange'] == 1){
			$resp->aa($openid, $uniacid, $id);
			//$this->testJumpUrl();
			return;
		}
		if(time()-$member['mediatime']>60*60*20*3){
			$resp->sendText($openid, '您的二维码已过期，正在为你重新生成...');
			$resp->aa($openid, $uniacid, $id);
			//$this->testJumpUrl();
			return;
		}
		if(empty($member['media_id'])){
			$target_file = IA_ROOT.'/addons/hc_hunxiao/qrcode/qrshare/'.$uniacid."share$id.jpg";
			if(!file_exists($target_file)){
				$resp->aa($openid, $uniacid, $id);
				//$this->testJumpUrl();
			} else {
				$media_id = $resp->uploadImage($target_file);
				pdo_update('hc_hunxiao_member', array('media_id'=>$media_id, 'ischange'=>0, 'mediatime'=>time()), array('id'=>$id));
				$ret = $resp->sendImage($openid, $media_id);
			}
		} else {
			return $this->respImage($member['media_id']);	
		}
    }
	
	private function testJumpUrl(){
		global $_W;
		//异步执行
		$host = $_SERVER['HTTP_HOST'];
		$fp = fsockopen($host, 80, $errno, $errstr, 1);
		if (!$fp) { 
			WeUtility::logging("fsockopen错误", $out);
			echo "$errstr ($errno)<br />\n";
		} else {
			$url = "/app/".$this->createMobileUrl('RunTask', array('from_user'=>$this->message['from']));
			$out = "GET $url  / HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			 WeUtility::logging("来到fwrite", $out);
			fwrite($fp, $out);
			/*忽略执行结果
			while (!feof($fp)) {
				echo fgets($fp, 128);
			}*/
			fclose($fp);
			$this->responseEmptyMsg();
		}
	}
	
	public function responseEmptyMsg() {
		global $_W;
		WeUtility::logging("responseEmptyMsg", "sdfsdfsdfsdf");
		ob_clean();
		ob_start();
		echo '';
		ob_flush();
		ob_end_flush();
		exit(0);
	}
}
