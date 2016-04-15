<?php

defined('IN_IA') or exit('Access Denied');

define('JX_ROOT', str_replace("\\", '/', dirname(__FILE__)));

require IA_ROOT . '/addons/xkd_fkz/define.php';
require APP_PHP . 'wechatutil.php';
require APP_PHP . 'wechatapi.php';
require APP_PHP . 'usermanager.php';
require_once APP_PHP . 'responser.php';

class Xkd_fkzModuleProcessor extends WeModuleProcessor
{

    private $mod_poster;
    private $mod_qr;
    private $mod_member;
    private $mod_commission;

    public function respond()
    {

        $this->loadMod('poster');
        $this->mod_poster = new poster();

        $this->loadMod('qr');
        $this->mod_qr = new qr();

        $this->loadMod('member');
        $this->mod_member = new member();

        WeUtility::logging('message xx', json_encode($this->message));
        $this->refreshUserInfo($this->message['from']);
        WeUtility::logging("Processor:SUBSCRIBE", $this->message);


        if ($this->message['msgtype'] == 'text' || ($this->message['msgtype'] == 'event' and $this->message['event'] == 'CLICK')) {
            $resp = $this->respondText();
        } else if ($this->message['msgtype'] == 'event') {
            if ($this->message['event'] == 'unsubscribe') {
                return $this->responseEmptyMsg();
            } else if ($this->message['event'] == 'subscribe') {
                $scene_id = $this->message['eventkey'];
                WeUtility::logging("Processor:SUBSCRIBE", $scene_id);
                $resp = $this->respondSubscribe();
                WeUtility::logging("Processor:SUBSCRIBE done", $scene_id);
            } elseif ($this->message['event'] == 'SCAN') {
                $scene_id = $this->message['eventkey'];
                WeUtility::logging("Processor:SCAN", $scene_id);
                $resp = $this->respondScan();
                WeUtility::logging("Processor:SCAN done", $scene_id);
            }
        }
        return $resp;
    }

    private function respondText()
    {
        global $_W;

        $member = pdo_fetch("select * from " . tablename('jiexi_aaa_member') . " where openid=:openid", array(
            'openid' => $this->message['from']
        ));
        $cfg = $this->module['config'];

        if (empty($member['parent1']) && $member['uid'] != $cfg['uid']) {
            return $this->respText("您不是通过扫码推荐的，请取消关注，扫描推荐二维码重新关注");
        }

        if (empty($member['level'])) {
            return $this->respText("您现在还不是正式会员，请升级后再来获取自己的推广名片");
        }

        WeUtility::logging("Create Qrcode 1", $this->message);
        $url = $_W['siteroot'] . WechatUtil::createMobileUrl('qrcode', $this->modulename, array('openid' => $this->message['from']));

        $ret = WechatUtil::http_request($url, null, null, 4000);
        WeUtility::logging("Create Qrcode 2", $url . "==>" . json_encode($ret));

        return $this->responseEmptyMsg();
    }

    private function responseEmptyMsg()
    {
        ob_clean();
        ob_start();
        echo '';
        ob_flush();
        ob_end_flush();
        exit(0);
    }

    private function refreshUserInfo($from_user)
    {
        $qr_mgr = new UserManager('');
        $userInfo = fans_search($from_user, array('nickname', 'avatar'));
        if (empty($userInfo) || empty($userInfo['nickname']) || empty($userInfo['avatar'])) {
            $weapi = new WechatAPI();
            $userInfo = $weapi->getUserInfo($from_user);
            if (!isset($userInfo['subscribe']) || $userInfo['subscribe'] != 1) {
                return;
            }
            WeUtility::logging('saveUserInfo', $userInfo);
            $from_user = $userInfo['openid'];
            load()->model('mc');
            $uid = mc_openid2uid($from_user);
            mc_update($uid, array(
                'nickname' => $userInfo['nickname'],
                'gender' => $userInfo['sex'],
                'nationality' => $userInfo['country'],
                'resideprovince' => $userInfo['province'],
                'residecity' => $userInfo['city'],
                'avatar' => $userInfo['headimgurl']
            ));
        }
        WeUtility::logging('refresh', $userInfo);
    }

    private function respondScan()
    {
        $scene_id = $this->message['eventkey'];
        if (empty($scene_id)) {
            WeUtility::logging('subscribe', 'no scene id');
            return $this->respText('欢迎关注微信号!');
        }

        WeUtility::logging('getQRByScene', $scene_id);
        $qr_mgr = new UserManager('');
        $qr = $this->mod_qr->get_qr_by_scene($scene_id);
        if (empty($qr)) {
            return $this->respText('欢迎回来，您已经关注');
        }
        $poster = $this->mod_poster->get_poster($qr['poster_id']);
        if (empty($poster)) {
            return $this->respText('欢迎回来，您已经关注');
        }

        $userInfo = fans_search($this->message['from'], array('uid', 'nickname', 'avatar'));
        $member = $this->mod_member->get_member($userInfo['uid']);
        $parent = $this->mod_member->get_member($member['parent1']);

        if (empty($parent)) {
            return $this->respText("由于您之前不是通过扫描推荐二维码关注的，请取消关注，扫描推荐二维码重新关注");
        }

        $followstr = $poster['follow'];
        $followstr = str_replace('[nickname]', $member['nickname'], $followstr);
        $followstr = str_replace('[parentnickname]', $parent['nickname'], $followstr);
        $followstr = str_replace('[uid]', $member['uid'], $followstr);
        return $this->respText($followstr);
    }

    private function respondSubscribe()
    {
        global $_W;
        load()->model('mc');
        $cfg = $this->module['config'];

        $follower_openid = $this->message['from'];
        list($dummy, $scene_id) = explode('_', $this->message['eventkey']);

        $follower_manager = new UserManager('');
        $weapi = new WechatAPI();
        $userInfo = $weapi->getUserInfo($follower_openid);
        $follower_manager->saveUserInfo($userInfo);

        $follower_id = mc_openid2uid($follower_openid);
        if (empty($follower_id)) exit;

        $isnew = false;
        $follower_extend = $this->mod_member->get_member_by_openid($follower_openid);
        WeUtility::logging('getMembersExtend', $follower_extend);

        if (empty($follower_extend)) {

            $isnew = true;

            $follower_extend = array(
                'uid' => $follower_id,
                'uniacid' => $_W['uniacid'],
                'openid' => $follower_openid,
                'level' => 0,
                'add_time' => TIMESTAMP
            );

            $this->mod_member->add_member($follower_extend);
        } elseif (empty($follower_extend['parent1'])) {
            $isnew = true;
        }

        if (empty($scene_id)) {
            WeUtility::logging('subscribe', 'no scene id');
            return $this->respText('欢迎关注微信号!');
        }

        WeUtility::logging('getQRByScene', $scene_id);
        $qr = $this->mod_qr->get_qr_by_scene($scene_id);
        if (empty($qr)) {
            WeUtility::logging('subscribe', 'qr not found using scene ' . $scene_id);
            return $this->respText('欢迎关注微信号!');
        }

        WeUtility::logging('getPoster', $qr['poster_id']);
        $poster = $this->mod_poster->get_poster($qr['poster_id']);
        if (empty($poster)) {
            WeUtility::logging('subscribe', 'poster not found using poster ' . $qr['poster_id']);
            return $this->respText('欢迎关注微信号!');
        }

        $parent_openid = $qr['openid'];
        WeUtility::logging('get parent', $parent);
        $parent = $this->mod_member->get_member_by_openid($parent_openid);

        if ($isnew) {
            WeUtility::logging('record followship', $qr);

            if (!empty($parent)) {
                $data = array();
                $data['parent1'] = $parent['uid'];
                $data['parent2'] = $parent['parent1'];
                $data['parent3'] = $parent['parent2'];
                $data['parent4'] = $parent['parent3'];
                $data['parent5'] = $parent['parent4'];
                $data['parent6'] = $parent['parent5'];
                $data['parent7'] = $parent['parent6'];
                $data['parent8'] = $parent['parent7'];
                $data['parent9'] = $parent['parent8'];
                $data['parent10'] = $parent['parent9'];
                $data['parent11'] = $parent['parent10'];
                $data['parent12'] = $parent['parent11'];

                $this->mod_member->update_member($follower_extend['uid'], $data);
                WeUtility::logging('update member', $follower_extend);
            }
        }

        WeUtility::logging('send welcome', $poster);
        $followstr = $poster['follow'];
        $followstr = str_replace('[nickname]', $userInfo['nickname'], $followstr);
        $followstr = str_replace('[parentnickname]', $parent['nickname'], $followstr);
        $followstr = str_replace('[uid]', $follower_extend['uid'], $followstr);
        return $this->respText($followstr);
    }

    private function loadMod($class)
    {
        require_once JX_ROOT . '/mod/' . $class . '.mod.php';
    }
}