<?php
/**
 * 女神来了模块订阅器
 *
 */


defined('IN_IA') or exit('Access Denied');
class Fm_photosvoteModuleReceiver extends WeModuleReceiver
{
    public function receive()
    {
        global $_W, $_GPC;
        $type    = $this->message['type'];
        $uniacid = $_W['uniacid'];
        $acid    = $_W['acid'];
        $openid  = $this->message['from'];
        $event   = $this->message['event'];
        $cfg     = $this->module['config'];
        if ($event == 'unsubscribe') {
            $record = array(
                'updatetime' => TIMESTAMP,
                'follow' => '0',
                'unfollowtime' => TIMESTAMP
            );
            pdo_update('mc_mapping_fans', $record, array(
                'openid' => $openid,
                'acid' => $acid,
                'uniacid' => $uniacid
            ));
            if ($cfg['isopenjsps']) {
                $fmvotelog = pdo_fetchall("SELECT tfrom_user FROM " . tablename('fm_photosvote_votelog') . " WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(
                    ':from_user' => $openid,
                    ':uniacid' => $uniacid
                ));
                foreach ($fmvotelog as $log) {
                    $fmprovevote = pdo_fetch("SELECT photosnum,hits FROM " . tablename('fm_photosvote_provevote') . " WHERE from_user = :from_user and uniacid = :uniacid LIMIT 1", array(
                        ':from_user' => $log['tfrom_user'],
                        ':uniacid' => $uniacid
                    ));
                    pdo_update('fm_photosvote_provevote', array(
                        'lasttime' => TIMESTAMP,
                        'photosnum' => $fmprovevote['photosnum'] - 1,
                        'hits' => $fmprovevote['hits'] - 1
                    ), array(
                        'from_user' => $log['tfrom_user'],
                        'uniacid' => $uniacid
                    ));
                }
                pdo_delete('fm_photosvote_votelog', array(
                    'from_user' => $openid
                ));
                pdo_delete('fm_photosvote_bbsreply', array(
                    'from_user' => $openid
                ));
            }
        } elseif ($event == 'subscribe') {
            if ($cfg['oauthtype'] == 2) {
                $arrlog       = pdo_fetch("SELECT * FROM " . tablename('mc_mapping_fans') . " WHERE uniacid = :uniacid AND openid = :openid", array(
                    ':uniacid' => $_W['uniacid'],
                    ':openid' => $openid
                ));
                $access_token = WeAccount::token();
                $url          = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $html         = file_get_contents($url);
                $re           = @json_decode($html, true);
                if (!empty($arrlog)) {
                    $data = array(
                        'nickname' => $re['nickname'],
                        'unionid' => $re['unionid']
                    );
                    pdo_update('mc_mapping_fans', $data, array(
                        'openid' => $openid
                    ));
                } else {
                    $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' . tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(
                        ':uniacid' => $_W['uniacid']
                    ));
                    $nickname        = $re['nickname'];
                    $data            = array(
                        'uniacid' => $_W['uniacid'],
                        'nickname' => $re['nickname'],
                        'avatar' => $re['headimgurl'],
                        'groupid' => $default_groupid,
                        'createtime' => TIMESTAMP
                    );
                    pdo_insert('mc_members', $data);
                    $id   = pdo_insertid();
                    $data = array(
                        'nickname' => $re['nickname'],
                        'unionid' => $re['unionid'],
                        'uid' => $id
                    );
                    pdo_update('mc_mapping_fans', $data, array(
                        'openid' => $openid
                    ));
                }
            }
        }
    }
}