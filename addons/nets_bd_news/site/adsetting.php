<?php
global $_GPC, $_W;
		$cert_path = ATTACHMENT_ROOT . '/fytcert/';
		if (!file_exists($cert_path)){ mkdir ($cert_path);}//创建证书文件夹
		$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
		$uid=$_W["uid"];
		$uniacid=$_W['uniaccount']['uniacid'];
		$record=pdo_fetch("SELECT * FROM ".tablename('netsbd_set')." WHERE uniacid=".$uniacid);
		$iscert=0;
		$iskey=0;
		$isca=0;
		if(file_exists($cert_path."apiclient_cert.pem.".$uniacid)){
			$iscert=1;
		}
		if(file_exists($cert_path."apiclient_key.pem.".$uniacid)){
			$iskey=1;
		}
		if(file_exists($cert_path."rootca.pem.".$uniacid)){
			$isca=1;
		}
		if ($operation == 'display') {
			include $this->template('adsetting');
		} elseif ($operation == 'post') {
				$r["list_ad_top"]=$_GPC['list_ad_top'];
				$r["list_ad_middle"]=$_GPC['list_ad_middle'];
				$r["list_ad_bottom"]=$_GPC['list_ad_bottom'];
				$r["detail_ad_top"]=$_GPC['detail_ad_top'];
				$r["detail_ad_middle"]=$_GPC['detail_ad_middle'];
				$r["detail_ad_bottom"]=$_GPC['detail_ad_bottom'];
				$r["share_title"]=$_GPC['share_title'];
				$r["share_img"]=$_GPC['share_img'];
				$r["share_desc"]=$_GPC['share_desc'];
				$r["follow_ico"]=$_GPC['follow_ico'];
				$r["follow_url"]=$_GPC['follow_url'];
				$r["follow_title"]=$_GPC['follow_title'];
				
				//详细页头部广告
				$r["list_ad_top"]=$_GPC['list_ad_top'];
				$r["list_ad_middle"]=$_GPC['list_ad_middle'];
				$r["list_ad_bottom"]=$_GPC['list_ad_bottom'];
				if($_GPC["adhead_1"]=="2"){
					$r["list_ad_top"]="";
					$r["list_ad_middle"]="";
					$r["list_ad_bottom"]=$_GPC['list_ad_bottom1'];
				}
				//详细页底部广告
				$r["detail_ad_top"]=$_GPC['detail_ad_top'];
				$r["detail_ad_middle"]=$_GPC['detail_ad_middle'];
				$r["detail_ad_bottom"]=$_GPC['detail_ad_bottom'];
				if($_GPC["adfoot_1"]=="2"){
					$r["detail_ad_top"]="";
					$r["detail_ad_middle"]="";
					$r["detail_ad_bottom"]=$_GPC['detail_ad_bottom1'];
				}
				$r["mchid"]=$_GPC['mchid'];
				//var_dump($_FILES['weixin_cert_file']);
				if(!empty($_FILES['weixin_cert_file'])){
					file_upload($_FILES['weixin_cert_file'], 'pem', 'apiclient_cert.pem');
				}
				if(!empty($_FILES['weixin_key_file'])){
					file_upload($_FILES['weixin_key_file'], 'pem', 'apiclient_key.pem');
				}
				if(!empty($_FILES['weixin_root_file'])){
					file_upload($_FILES['weixin_root_file'], 'pem', 'rootca.pem');
				}
				$b=true;
				if(!empty($_GPC['cert'])) {
					$content=$_GPC['cert'];//file_get_contents($cert_path . 'apiclient_cert.pem.' . $uniacid);
					$ret = file_put_contents($cert_path . 'apiclient_cert.pem.' . $uniacid, trim($content));
					$b = $b && $ret;
				}
				if(!empty($_GPC['key'])) {
					$content=$_GPC['key'];//file_get_contents($cert_path . 'apiclient_key.pem.' . $uniacid);
					$ret = file_put_contents($cert_path . 'apiclient_key.pem.' . $uniacid, trim($content));
					$b = $b && $ret;
				}
				if(!empty($_GPC['ca'])) {
					$content=$_GPC['ca'];//file_get_contents($cert_path . 'rootca.pem.' . $uniacid);
					$ret = file_put_contents($cert_path . 'rootca.pem.' . $uniacid, trim($content));
					$b = $b && $ret;
				}
				$r["createtime"]=TIMESTAMP;
			if(empty($record)){
				$r["uid"]=$uid;
				$r["uniacid"]=$uniacid;
				pdo_insert("netsbd_set",$r);
			}else{
				$r["createtime"]=TIMESTAMP;
				pdo_update("netsbd_set",$r,array('id' => $record["id"]));
			}
			message('保存成功！', $this->createWebUrl('Hxsadset', array('op' => 'display')), 'success');
			include $this->template('adsetting');
		}
//证书文件上传
function file_upload($file, $type = 'pem', $name = '') {
	$harmtype = array('pem');
	if (empty($file)) {
		return error(-1, '没有上传内容');
	}
	if (!in_array($type, array('pem'))) {
		return error(-2, '未知的上传类型');
	}
	global $_W;
	$result = array();
	$uniacid = intval($_W['uniaccount']['uniacid']);
	$path = "fytcert/".$name.".".$uniacid;
	$result['path'] = $path;
	if (!file_move($file['tmp_name'], ATTACHMENT_ROOT . '/' . $result['path'])) {
		return error(-1, '保存上传文件失败');
	}
	$result['success'] = true;
	return $result; 
}
function file_move($filename, $dest) {
	//print("<br/>".$filename);
	//print("<br/>".$dest);
	global $_W;
	if (is_uploaded_file($filename)) {
		move_uploaded_file($filename, $dest);
	} else {
		//rename($filename, $dest);
	}
	@chmod($filename, $_W['config']['setting']['filemode']);
	return is_file($dest);
}
?>