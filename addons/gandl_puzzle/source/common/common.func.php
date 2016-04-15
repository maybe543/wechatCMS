<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

/**
 * ajax操作错误跳转的快捷方法
 */
function returnError($message,$data='',$status=0) {
	header('Content-Type:application/json; charset=utf-8');
	$ret=array(
		'status'=>$status,
		'info'=>$message,
		'data'=>$data
	);
	exit(json_encode($ret));
}

/**
 * ajax操作成功跳转的快捷方法
 */
function returnSuccess($message,$data='',$status=1) {
	header('Content-Type:application/json; charset=utf-8');
	$ret=array(
		'status'=>$status,
		'info'=>$message,
		'data'=>$data
	);
	exit(json_encode($ret));
}

/* 时间格式化 */
function time_to_text($s){
	$t='';
	if($s>86400){
		$t.=intval($s/86400)."天";
		$s=$s%86400;
	}
	if($s>3600){
		$t.=intval($s/3600)."小时";
		$s=$s%3600;
	}
	if($s>60){
		$t.=intval($s/60)."分钟";
		$s=$s%60;
	}
	if($s>0){
		$t.=intval($s)."秒";
	}
	return $t;
}

/** 在输入的字符范围内生成随机码 **/
function rand_words($src,$len){
	$randStr = str_shuffle($src);
	return substr($randStr,0,$len);
}

/** url参数加密解密 **/
function url_base64_encode($str){
	$str=base64_encode($str);
	$code=url_encode($str);
	return $code;//$code='dHQ!'
}
function url_encode($code){
	$code=str_replace('+',"!",$code);//把所用"+"替换成"!"
	$code=str_replace('/',"*",$code);//把所用"/"替换成"*"
	$code=str_replace('=',"",$code);//把所用"="删除掉
	return $code;//$code='dHQ!'
}
function url_base64_decode($code){
	$code=url_decode($code);
	$str=base64_decode($code);
	return $str;
}
function url_decode($code){
	$code=str_replace("!",'+',$code);//把所用"+"替换成"!"
	$code=str_replace("*",'/',$code);//把所用"/"替换成"*"
	return $code;//$code='dHQ!'
}
function pencode($code,$seed='gengli9876543210'){
	$c=url_base64_encode($code);
	$pre=substr(md5($seed.$code),0,3);
	return $pre.$c;
}
function pdecode($code,$seed='gengli9876543210'){
	if(empty($code) || strlen($code)<=3){
		return "";
	}
	$pre=substr($code,0,3);
	$c=substr($code,3);
	$str=url_base64_decode($c);
	$spre=substr(md5($seed.$str),0,3);
	if($spre==$pre){
		return $str;
	}else{
		return "";
	}
}
/** url参数加密解密 **/

/* 根据原图地址获取相应头像图片地址 未知来源图 size:s(50px左右) m(120px左右) */ 
function VP_AVATAR($src,$size='s'){
	if(empty($src) || empty($size)){
		return $src;
	}else{
		// 分析src来源
		$parts = parse_url($src);
		if($parts['host']=='wx.qlogo.cn'){// 来自微信的头像
			if($size=='s'){
				$size='64';
			}else if($size=='m'){
				$size='132';
			}
			

			$src=substr($src, 0, strrpos($src,'/')).'/'.$size;
		}else{
			// TODO 目前暂时没有来自其他来源的头像
		}
		return $src;
	}
}