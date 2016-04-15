<?php
/**
 * Created by IntelliJ IDEA.
 * User: codeMonkey QQ:631872807
 * Date: 2015/6/8
 * Time: 8:35
 */

global $_W,$_GPC;
MonUtil::checkmobile();
$sid=$_GPC['sid'];
$shake=DBUtil::findById(DBUtil::$TABLE_QMSHAKE,$sid);
if(empty($shake)) {
	message("摇一摇活动删除或已不存在");
}
$follow=1;

$openid = $_W['fans']['from_user'];

if (empty($openid)) {
	//模板消息传进来
	$openid = $_GPC['topenid'];
}

$from = $_GPC['from'];
$cookieAuth = array();
if ($from == 'fm_photosvote') { //来自投票
	$openid  = $_GPC['oid'];
	$cookieAuth['fm'] = "fm_photosvote";
	$cookieAuth['oid'] = $openid;
	$authCookie = base64_encode(json_encode($cookieAuth));
    isetcookie("authFrom", $authCookie, 24 * 3600 * 365);
	
} else {
	$cookieAuth['fm'] = "nomal";
	$from ='nomal';
	$authCookie = base64_encode(json_encode($cookieAuth));
    isetcookie("authFrom", $authCookie, 24 * 3600 * 365);
}

if (!empty($openid)) {//存起来
	//$cookieAuth['oid'] = $openid;
	//$authCookie = base64_encode(json_encode($cookieAuth));
	//isetcookie("authFrom", $authCookie, 24 * 3600 * 365);
}

if (!empty($_W['fans']['follow'])){
	$follow=2;
}

//$openid ="o_-Hajq-MxgT-pvJX7gRMswH8_eM";
if (!empty($openid))
{
	$user = DBUtil::findUnique(DBUtil::$TABLE_QMSHAKE_USER,array(":openid"=>$openid,":sid"=>$sid));

	if (!empty($user))
	{
		$userPrizes =pdo_fetchall('SELECT r.*,p.pname as pname,p.price as price,p.p_summary as summary,p.pimg as pimg,p.tgs as tgs ,p.tgs_url  tgs_url  FROM ' . tablename(DBUtil::$TABLE_QMSHAKE_RECORD) . " r left join ".tablename(DBUtil::$TABLE_QMSHAKE_PRIZE)." p on r.pid=p.id WHERE  r.sid=:sid and r.openid=:openid and r.pid <> 0 ", array(':sid' => $sid, ":openid" => $openid));
	}

}

include $this->template("shake");