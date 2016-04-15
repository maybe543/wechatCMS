<?php
global $_W, $_GPC;
if(!empty($this->module['config']['appid'])&&!empty($this->module['config']['appsecret'])) {
	$this->auth();
}else{
	$user_agent  = $_SERVER['HTTP_USER_AGENT'];
	if (strpos($user_agent, 'MicroMessenger') === false) {
		die("本页面仅支持微信访问!非微信浏览器禁止浏览!");
	}
}
$id=intval($_GPC['id']);
$openid=$_W['openid'];
$uniacid=$_W['uniacid'];
$pid=$id;
//浏览量++
//企业信息
$com=pdo_fetch("select * from ".tablename('enjoy_recuit_culture')." where uniacid = '{$_W['uniacid']}'");
//先查询看看此人有没有浏览过这个职位
$res=pdo_fetchcolumn("select count(*) from ".tablename('enjoy_recuit_view')." where uniacid = '{$_W['uniacid']}' and pid=".$id." and openid='".$openid."'");

if(Intval($res)>0){
	//已经浏览过了这个职位
		
}else{

	$data=array(
			'uniacid'=>$_W['uniacid'],
			'openid'=> $_W['openid'],
			'pid'=>$id,
			'time'=>TIMESTAMP
	);
	//插入数据
	pdo_insert('enjoy_recuit_view', $data);
	pdo_query("update ".tablename('enjoy_recuit_position')." set views=views+1 where uniacid = '{$_W['uniacid']}' and id=".$id);
}

//判断这个人七天内有没有投递过这个职位
$ctime=pdo_fetchcolumn("select createtime from ".tablename('enjoy_recuit_deliver')." where uniacid=".$uniacid." and openid='".$openid."' and pid=".$pid." order by createtime desc");
$time7=7*24*60*60;
$time=Intval($ctime)+$time7;

if($time<=TIMESTAMP){
	//可以投递
	$vote=1;
		
}else{
	//查看记录
	$vote=0;
		
}

//职位详细信息
$item=pdo_fetch("select * from ".tablename('enjoy_recuit_position')." as a left join ".tablename('enjoy_recuit_position_range')." as b on a.id=b.pid WHERE a.uniacid = '{$_W['uniacid']}' and a.id=".$id);
//var_dump($list);
//热门职位推荐
$hotlist=pdo_fetchall("select * from ".tablename('enjoy_recuit_position')." WHERE uniacid = '{$_W['uniacid']}' order by hot desc limit 3");

//链接地址
//查询看看是否已经录入自己的基本信息
$openid=$_W['openid'];
//分享信息
$sharelink =  $_W['siteroot'] . "app/".$this->createMobileUrl('pdetail', array('id' => $item['id']));
//$sharetitle = $com['cname'].'-'.$item['pname'];
$sharetitle = str_replace('{cname}', $com['cname'], $com['share_title']);
$sharetitle = str_replace('{pname}', $item['pname'], $sharetitle);
//$activity['tag']['label'] = str_replace('{nickname}', $owner['nickname'], $activity['tag']['label']);
//$sharedesc = '这个职位很适合你，快来投递吧！';
$sharedesc = str_replace('{cname}', $com['cname'], $com['share_desc']);
$sharedesc = str_replace('{pname}', $item['pname'], $sharedesc);
$shareimg = tomedia($com['share_icon']);

include $this->template('pdetail');