<?php
global $_GPC, $_W;
//BEGIN 验证是否登录，并取出uid
$cert_path = ATTACHMENT_ROOT . '/adpicture/';
if (!file_exists($cert_path)){ mkdir ($cert_path);}//创建证书文件夹
		
if(empty($_W["member"]["uid"]) && empty($_GPC["hxs_uid"])){
	$loginurl=url('auth/login', array('forward' => base64_encode($_SERVER['QUERY_STRING'])), true);
	Header("Location: $loginurl"); 
}
//会员信息
$uid=$_W["member"]["uid"];
if(!empty($_GPC["hxs_uid"])){
	$uid=$_GPC["hxs_uid"];
}
//END
if(empty($uid)){
	$uid=0;
}
if(!empty($_GPC['post'])){
	$ad_title=$_GPC["ad_title"];
	$adpicture=$_GPC["adpicture"];
	$adurl=$_GPC["adurl"];
	$adprice=$_GPC["adprice"];
	$ad_picture="";
	if(!empty($_FILES['adpicture'])){
		$ad_picture=TIMESTAMP.".jpg";
		file_upload($_FILES['adpicture'], '', $ad_picture);
	}
	$ad["uid"]=$uid;
	$ad["uniacid"]=$_W['uniacid'];
	$ad["click_price"]=$adprice;
	$ad["title"]=$ad_title;
	$ad["picture"]=$ad_picture;
	$ad["url"]=$adurl;
	$ad["state"]=0;
	$ad["click_num"]=0;
	$ad["createtime"]=TIMESTAMP;
	pdo_insert("netsbd_adlist",$ad);
}
if(!empty($_GPC['del'])){
	
	pdo_delete("netsbd_adlist",array("id"=>$_GPC['del']));
}
$members=pdo_fetch("select * from ims_mc_members where uid=".$uid);

$adlist=pdo_fetchall("select * from ims_netsbd_adlist where uid=".$uid." ORDER BY id DESC");
$adprice=pdo_fetchall("select * from ims_netsbd_ad_price where uniacid=".$_W['uniacid']." ORDER BY click_price");

include $this->template('ad');
function file_upload($file, $type = 'jpg', $name = '') {
	$harmtype = array('pem');
	if (empty($file)) {
		return error(-1, '没有上传内容');
	}
	global $_W;
	$result = array();
	$uniacid = intval($_W['uniaccount']['uniacid']);
	$path = "adpicture/".$name.".".$type;
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