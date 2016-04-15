<?php 
function saveMedia($url){
	global $_W;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);    
	curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$package = curl_exec($ch);
	$httpinfo = curl_getinfo($ch);
   
	curl_close($ch);
	$media = array_merge(array('mediaBody' => $package), $httpinfo);
	//求出文件格式
	preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
	$extAry=array('jpg','jpeg','png','gif','bmp');
	$fileExt = strtolower($extmatches[1]);
	if(!in_array($fileExt,$extAry)){
		return 0;
	}
	$filename = time().rand(100,999).".{$fileExt}";
	$dirname = "../attachment/images/".$_W['uniacid'].'/'.date("Y")."/".date('m')."/";
	if(!file_exists($dirname)){
		mkdir($dirname,0777,true);
	}
	file_put_contents($dirname.$filename,$media['mediaBody']);
	return $dirname.$filename;
}
function deldir($dir) {
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}
function jetsum_fetch_token($openid="") {
	global $_GPC, $_W;
	load()->func('communication');
	$openid_s=$openid ? $openid : $_W['openid'];
	if(!$openid_s) return;
	if($_W['account']['level']<3)return 0;
	$Jetsumtoken="";
	if(is_array($_W['account']['access_token']) && !empty($_W['account']['access_token']['token']) && !empty($_W['account']['access_token']['expire']) && $_W['account']['access_token']['expire'] > TIMESTAMP) {
		$Jetsumtoken=$_W['account']['access_token']['token'];
	} else {
		if (empty($_W['account']['key']) || empty($_W['account']['secret'])) {
			return 0;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$_W['account']['key']}&secret={$_W['account']['secret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return 0;
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return 0;
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'];
		$row = array();
		$row['access_token'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $_W['account']['acid']));
		$Jetsumtoken= $record['token'];
	}
	$oauth3_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$Jetsumtoken."&openid=".$openid_s;
	$content = ihttp_get ( $oauth3_code );
	$token = @json_decode($content['content'], true);
	//return var_dump($content['content']);
	return $$token;
}
function jetsum_get_token_web() {
	global $_GPC, $_W;
	load()->func('communication');
	$account=pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
	$Jetsumtoken="";
	$acccount_acc=iunserializer($account['access_token']);
	if(is_array($acccount_acc) && !empty($acccount_acc['token']) && !empty($acccount_acc['expire']) && $acccount_acc['expire'] > TIMESTAMP) {
		$Jetsumtoken=$acccount_acc['token'];
	} else {
		if (empty($account['key']) || empty($account['secret'])) {
			return 1;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$account['key']}&secret={$account['secret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return 2;
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return 3;
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'];
		$row = array();
		$row['access_token'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $account['acid']));
		$Jetsumtoken= $record['token'];
	}
	return $Jetsumtoken;
}
function jetsum_fetch_token_web($openid="") {
	global $_GPC, $_W;
	load()->func('communication');
	$openid_s=$openid ? $openid : $_W['openid'];
	if(!$openid_s) return;
	$account=pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid",array(':uniacid'=>$_W['uniacid']));
	if($account['level']<3)return 0;
	$Jetsumtoken="";
	$acccount_acc=iunserializer($account['access_token']);
	
	if(is_array($acccount_acc) && !empty($acccount_acc['token']) && !empty($acccount_acc['expire']) && $acccount_acc['expire'] > TIMESTAMP) {
		$Jetsumtoken=$acccount_acc['token'];
	} else {
		if (empty($account['key']) || empty($account['secret'])) {
			return 1;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$account['key']}&secret={$account['secret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return 2;
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			return 3;
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'];
		$row = array();
		$row['access_token'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $account['acid']));
		$Jetsumtoken= $record['token'];
	}
	$oauth3_code = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$Jetsumtoken."&openid=".$openid_s;
	$content = ihttp_get ( $oauth3_code );
	$token = @json_decode($content['content'], true);
	//return var_dump($content['content']);
	return $token;
}
function j_member_fetch(){
	global $_GPC, $_W;
	if($_W['member']['uid'])return mc_fetch($_W['member']['uid']);
	$profile=pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE openid = :openid",array(":openid"=>$_W['openid']));
	if($profile['uid'])return pdo_fetch("SELECT * FROM ".tablename('mc_mapping_fans')." WHERE uid = :id",array(":id"=>$profile['uid']));
	//没有uid
	$p=jetsum_fetch_token();
	$avatar=$p['headimgurl'];
	$nickname=$p['nickname'];
	$gender=$p['gender'];
	$data=array(
		'uniacid'=>$_W['uniacid'],
		'createtime'=>TIMESTAMP,
		'nickname'=>$nickname,
		'avatar'=>$avatar,
		'gender'=>$gender,
		'salt'=>$profile['salt'],
		'lookingfor'=>$_W['openid'],
	);
	pdo_insert('mc_members',$data);
	$uid = pdo_insertid();
	pdo_update('mc_mapping_fans',array('uid'=>$uid),array('uniacid'=>$_W['uniacid'],'openid'=>$_W['openid']));
	return pdo_fetch("SELECT * FROM ".tablename('mc_members')." WHERE uid = :uid",array(":uid"=>$uid));
}
function j_memberAcatar_fetch_web($openid=""){
	$p=jetsum_fetch_token_web($openid);
	$data=array(
		'nickname'=>$p['nickname'],
		'avatar'=>$p['headimgurl'],
		'gender'=>$p['sex'],
	);
	return $data;
}
function j_memberAcatar_fetch($openid=""){
	$p=jetsum_fetch_token($openid);
	$data=array(
		'nickname'=>$p['nickname'],
		'avatar'=>$p['headimgurl'],
		'gender'=>$p['sex'],
	);
	return $data;
}
function j_member_update($param=array()){
	global $_W;
	if($_W['member']['uid'])mc_update($_W['member']['uid'],$param);
	j_member_fetch();
	pdo_update('mc_members',$param,array('lookingfor'=>$_W['openid']));
}
function j_tempeleSendMessage($id=0,$funid=0,$openid="",$data=array(),$remark="",$url="",$appids="",$appsecrets="") {
	global $_W;
	if(empty($openid))return array('-1','发送对象不能为空');
	if(empty($funid))return array('-1','活动ID不能为空');
	if(empty($id))return array('-1','模板ID不能为空');
	
	$item = pdo_fetch("SELECT * FROM ".tablename('j_tempmsg_temp')." where id='".$id."' ");
	$actItem = pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." where id='".$funid."' ");
	$userInfo = pdo_fetch("SELECT * FROM ".tablename('j_activity_winner')." where (from_user='".$openid."' or openid='".$openid."') and aid='".$funid."'  limit 1 ");
	if(empty($item) || empty($item['tempid']))return array('-1','数据库模板不能为空，请先添加');
	if(!empty($remark))$data['remark']=$remark;
	$temp=array();
	$itemary=json_decode($item['parama'],true);
	foreach($itemary as $key=>$val){
		if(!$data[$key]){
			$tempstr=str_replace("|#活动标题#|",$actItem['title'],$val['value']);
			$tempstr=str_replace("|#姓名#|",$userInfo['realname'],$tempstr);
			$tempstr=str_replace("|#活动时间#|",date('Y.m.d H:i',$actItem['starttime'])."至".date('m.d H:i',$actItem['endtime']),$tempstr);
			$tempstr=str_replace("|#活动地点#|",$actItem['address'],$tempstr);
			$tempstr=str_replace("|#签到时间#|",date('Y-m-d G:i:s'),$tempstr);
			$temp[$key]['value']=urlencode($tempstr);
		}else{
			$tempstr=str_replace("|#活动标题#|",$actItem['title'],$data[$key]);
			$tempstr=str_replace("|#姓名#|",$userInfo['realname'],$tempstr);
			$tempstr=str_replace("|#活动时间#|",date('Y.m.d H:i',$actItem['starttime'])."至".date('m.d H:i',$actItem['endtime']),$tempstr);
			$tempstr=str_replace("|#活动地点#|",$actItem['address'],$tempstr);
			$tempstr=str_replace("|#签到时间#|",date('Y-m-d G:i:s'),$tempstr);
			$temp[$key]['value']=urlencode($tempstr);
		}
		$temp[$key]['color']=isset($data[key]['color']) ? $data[key]['color'] : $val['color'] ? $val['color']: "#333333";
	}
	$sendData=array(
		"touser"=>$openid,
		"template_id"=>$item["tempid"],
		"url"=>$url,
		"topcolor"=>"#FF0000",
		"data"=>$temp,
	);
	load()->model('account');
	load()->func('communication');
	$accounts = pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid ORDER BY `level` DESC limit 1", array(':uniacid' => $_W['uniacid']));
	$appid=$accounts['key'];
	$appsecret=$accounts['secret'];
	if($accounts['level']!=4){
		if($appids && $appsecrets){
			$appid=$appids;
			$appsecret=$appsecrets;
		}
	}
	if(!$appid || !$appsecret)return array(-1,"系统参数缺少");
	
	$account=iunserializer($accounts['access_token']);
	if($account['expire']<TIMESTAMP){
		$urls = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret."";
		$content = ihttp_get($urls);
		if(is_error($content))return array('-1','更新token失败');
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			if(is_error($content))return array('-1','更新token失败');
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'];
		$row = array();
		$row['access_token'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $account['acid']));
		$account['token']= $record['token'];
	}
	$token=$account['token'];
	$sendapi = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$token;
	$response = ihttp_request($sendapi, urldecode(json_encode($sendData)));
	$response = json_decode($response['content'],true);
	if($response['errcode']!=0){
		return array('-1',$response['errmsg']);
	}else{
		return array('0',$response['msgid']);
	}
}
//***发送信息给客户***//
function jetsum_sendMessage($openid="",$content="",$aid="",$remark=""){
	global $_W;
	if(!$openid || !$content ||!$aid)return 0;
	$actItem=pdo_fetch("SELECT * FROM ".tablename('j_activity_reply')." WHERE rid = '".$aid."'");
	$userInfo=pdo_fetch("SELECT * FROM ".tablename('j_activity_winner')." WHERE aid = '".$actItem['id']."' and from_user='".$openid."' ");
	
	$tempstr=str_replace("|#活动标题#|",$actItem['title'],$content);
	$tempstr=str_replace("|#姓名#|",$userInfo['realname'],$tempstr);
	$tempstr=str_replace("|#昵称#|",$userInfo['nickname'],$tempstr);
	$tempstr=str_replace("|#性别#|",($userInfo['gender']==1? "先生":"女士"),$tempstr);
	$tempstr=str_replace("|#电话#|",$userInfo['mobile'],$tempstr);
	$tempstr=str_replace("|#活动时间#|",date('Y.m.d H:i',$actItem['starttime'])."至".date('m.d H:i',$actItem['endtime']),$tempstr);
	$tempstr=str_replace("|#活动地点#|",$actItem['address'],$tempstr);
	$tempstr=str_replace("|#签到时间#|",date('Y-m-d H:i',$userInfo['endtime']),$tempstr);
	$tempstr=str_replace("|#签到回调#|",$userInfo['reloadmsg'],$tempstr);
	
	$send=array(
		"touser"=>$openid,
		"msgtype"=>"text",
		'text' => array('content' => urlencode($tempstr)),
	);
	load()->model('account');
	load()->func('communication');
	$token=jetsum_get_token_web();
	$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
	$response = ihttp_request($url, urldecode(json_encode($send)));
	if(is_error($response)) {
		return false;
	}
	$result = @json_decode($response['content'], true);
	if(empty($result)) {
		return false;
	} elseif(!empty($result['errcode'])) {
		return false;
	}
	$data=array(
		"from_user"=>$openid,
		"weid"=>$_W['uniacid'],
		"content"=>$tempstr,
		"aid"=>$aid,
		"remark"=>$remark,
		"createtime"=>TIMESTAMP,
		'status'=>1,
	);
	pdo_insert("j_activity_msgrecord",$data);
	return true;
}

//***加密函数***//
/*
$str = 'abc'; 
$key = 'www.helloweba.com'; 
echo '加密:'.encrypt($str, 'E', $key); 
echo '解密：'.encrypt($str, 'D', $key);
 */
function encrypt($string,$operation,$key=''){ 
    $key=md5($key); 
    $key_length=strlen($key); 
      $string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string; 
    $string_length=strlen($string); 
    $rndkey=$box=array(); 
    $result=''; 
    for($i=0;$i<=255;$i++){ 
           $rndkey[$i]=ord($key[$i%$key_length]); 
        $box[$i]=$i; 
    } 
    for($j=$i=0;$i<256;$i++){ 
        $j=($j+$box[$i]+$rndkey[$i])%256; 
        $tmp=$box[$i]; 
        $box[$i]=$box[$j]; 
        $box[$j]=$tmp; 
    } 
    for($a=$j=$i=0;$i<$string_length;$i++){ 
        $a=($a+1)%256; 
        $j=($j+$box[$a])%256; 
        $tmp=$box[$a]; 
        $box[$a]=$box[$j]; 
        $box[$j]=$tmp; 
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256])); 
    } 
    if($operation=='D'){ 
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){ 
            return substr($result,8); 
        }else{ 
            return''; 
        } 
    }else{ 
        return str_replace('=','',base64_encode($result)); 
    } 
}
function __test(){
	return "哈哈哈哈哈哈哈哈哈哈哈哈";
}
?>