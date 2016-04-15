<?php

$op= $_GPC['op'] ? $_GPC['op'] : 'list';

$artid = intval($_GPC['artid']);

$acid=$_W['acid'];
$account = $uniaccount = array();
$uniaccount = pdo_fetch("SELECT * FROM ".tablename('uni_account')." WHERE uniacid = :uniacid", array(':uniacid' => $weid));
$acid = !empty($acid) ? $acid : $uniaccount['default_acid'];
$account = account_fetch($acid);

$detail = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE `id`=:id and weid=:weid", array(':id'=>$artid,':weid' => $weid));
$set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
$follow_url = $set['guanzhuUrl'];
load()->model('mc');
$userinfo = mc_oauth_userinfo();
$need_openid = true;
if ($_W['container'] != 'wechat') {
    if ($_GPC['do'] == 'admire') {
        $need_openid = false;
    }
}
if (empty($userinfo['openid']) && $need_openid) {
    die("<!DOCTYPE html>
                <html>
                    <head>
                        <meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'>
                        <title>抱歉，出错了</title><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1, user-scalable=0'><link rel='stylesheet' type='text/css' href='https://res.wx.qq.com/connect/zh_CN/htmledition/style/wap_err1a9853.css'>
                    </head>
                    <body>
                    <div class='page_msg'><div class='inner'><span class='msg_icon_wrp'><i class='icon80_smile'></i></span><div class='msg_content'><h4>请在微信客户端打开链接</h4></div></div></div>
                    </body>
                </html>");
}



    $adsets= pdo_fetchall("SELECT * FROM ".tablename('fineness_admire_set')." WHERE aid =$artid ORDER BY displayorder ASC limit 0,6 ");

    include $this->template('admire');






