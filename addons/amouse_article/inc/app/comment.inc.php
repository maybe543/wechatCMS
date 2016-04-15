<?php
global $_GPC, $_W;
$artid = intval($_GPC['artid']);

if(empty($artid)){
    $artid = intval($_GPC['id']);
}
$weid=$_W['uniacid'];
$detail = pdo_fetch("SELECT * FROM " . tablename('fineness_article') . " WHERE `id`=:id and weid=:weid", array(':id'=>$artid,':weid' => $weid));
$set=  pdo_fetch("SELECT * FROM ".tablename('fineness_sysset')." WHERE weid=:weid limit 1", array(':weid' => $weid));
$follow_url = $set['guanzhuUrl'];
$is_follow = false;
load()->model('mc');
$userinfo = mc_oauth_userinfo();
$need_openid = true;
if ($_W['container'] != 'wechat') {
    if ($_GPC['do'] == 'comment') {
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

$mycomments=pdo_fetchall("SELECT * FROM ".tablename('fineness_comment')." WHERE `aid`=:aid and weid=:weid AND openid=:openid order by createtime desc ", array(':aid'=>$artid, ':weid'
=>$weid,':openid'=>$userinfo['openid']));

include $this->template('comment');