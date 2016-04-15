<?php
global $_W,$_GPC;
load()->func('tpl');

if($_W['ispost']){
	$min = intval($_GPC['min']);
	$max = intval($_GPC['max']);
	
	$time = $_GPC['time'];
	$start = strtotime($time['start']);
	$end = strtotime($time['end']);
	
	$sql = "SELECT id FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND createtime > :start AND createtime < :end";
	$params = array(':uniacid'=>$_W['uniacid'],':start'=>$start,':end'=>$end);
	$topics = pdo_fetchall($sql,$params);
}

if(checksubmit('look')){
	foreach ($topics as $topic) {
		$random = rand($min,$max);
		$sql = "SELECT lnum FROM ".tablename('meepo_bbs_topics')." WHERE uniacid = :uniacid AND id = :id";
		$params = array(':uniacid'=>$_W['uniacid'],':id'=>$topic['id']);
		$num = pdo_fetchcolumn($sql,$params);
		
		pdo_update('meepo_bbs_topics',array('lnum'=>$num + $random),array('id'=>$topic['id']));
	}
	message('添加虚假浏览数据成功',referer(),'success');
}

if(checksubmit('good')){
	foreach ($topics as $topic) {
		$random = rand($min,$max);
		for ($i=0;$i<$random;$i++){
			pdo_insert('meepo_bbs_topic_like',array('tid'=>$topic['id'],'time'=>time(),'num'=>1));
		}
	}
	
	message('添加虚假点赞数据成功',referer(),'success');
}

if(checksubmit('share')){
	foreach ($topics as $topic) {
		$random = rand($min,$max);
		for ($i=0;$i<$random;$i++){
			pdo_insert('meepo_bbs_topic_share',array('tid'=>$topic['id'],'time'=>time(),'num'=>1));
		}
	}
	message('添加虚假分享数据成功',referer(),'success');
}

include $this->template('random');