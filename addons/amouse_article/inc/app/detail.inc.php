<?php

$id = intval($_GPC['id']);

$acid=$_W['acid'];

$account = $uniaccount = array();
$uniaccount = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :uniacid", array(':uniacid' => $weid));
$acid = !empty($acid) ? $acid : $uniaccount['default_acid'];
$account = account_fetch($acid);

$detail = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE `id`=:id and weid=:weid", array(':id'=>$id,':weid' => $weid));

$where = " WHERE `aid`=$id and weid=$weid " ;
if($set && $set['iscomment']==1){//不开启审核
    $where.=" and status=1 ";
}
$cList = pdo_fetchall("SELECT * FROM " . tablename('fineness_comment') .$where );
if (!empty($detail)) {
    pdo_update('fineness_article', array('clickNum' => $detail['clickNum'] + 1), array('id' => $detail['id']));
}
$shareimg = toimage($detail['thumb']);
$url=$_W['siteroot']."app/".substr($this->createMobileUrl('detail',array('id'=>$id,'uniacid'=>$weid),true),2);
if($detail['bg_music_switch']==1){
    if (strexists($detail['musicurl'], 'http://')||strexists($detail['musicurl'], 'https://')) {
        $detail['musicurl'] = $detail['musicurl'];
    } else {
        $detail['musicurl'] = $_W['attachurl'] . $detail['musicurl'];
    }
}
if (!empty($detail['outLink'])) {
    if(strtolower(substr($detail['outLink'], 0, 4)) != 'tel:' && !strexists($detail['outLink'], 'http://') && !strexists($detail['outLink'], 'https://')) {
        $detail['outLink'] = $_W['siteroot'] . 'app/' . $detail['outLink'];
    }
    header('Location: '. $detail['outLink']);
    exit;
}

$sql = "SELECT * FROM `ims_fineness_adv_er` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `ims_fineness_adv_er`)-(SELECT MIN(id) FROM `ims_fineness_adv_er`))+(SELECT MIN(id) FROM `ims_fineness_adv_er`)) AS id) AS t2 WHERE t1.id >= t2.id ORDER BY t1.id LIMIT 1";
$randAdv = pdo_fetch($sql);
if($randAdv){
    $randAdv['thumb'] = (strpos($randAdv['thumb'], 'http://') === FALSE) ? tomedia($randAdv['thumb']) : $randAdv['thumb'];
}
$wechat=  pdo_fetch("SELECT * FROM ".tablename('account_wechats')." WHERE acid=:acid AND uniacid=:uniacid limit 1", array(':acid' => $weid,':uniacid' => $weid));

$admires=pdo_fetchall("SELECT * FROM ".tablename('fineness_admire')." WHERE `aid`=:aid and weid=:weid order by id desc ", array(':aid'=>$detail['id'],':weid'=>$weid));


if(!empty($detail['template'])) {
    include $this->template($detail['templatefile']);
    exit;
}
include $this->template('themes/detail5');
exit;