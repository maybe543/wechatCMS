<?php
 defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/quicklink/define.php';
require_once IA_ROOT . '/addons/quickcenter/loader.php';
class QuickLinkModule extends WeModule{
    public function fieldsFormDisplay($rid = 0){
        global $_W;
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $allChannel = $_channel -> batchGet($_W['weid']);
        if ($rid){
            yload() -> classs('quicklink', 'channelreply');
            $_channelreply = new ChannelReply();
            $reply = $_channelreply -> get($rid);
            $channel = $_channel -> get($_W['weid'], $reply['channel']);
        }
        include $this -> template('form');
    }
    public function fieldsFormValidate($rid = 0){
        global $_W, $_GPC;
        if (isset($_GPC['channel_id'])){
            $channel_id = intval($_GPC['channel_id']);
            if (!empty($channel_id)){
                yload() -> classs('quicklink', 'channel');
                $_channel = new Channel();
                $channel = $_channel -> get($_W['weid'], $reply['channel']);
                if (!empty($channel)){
                    return;
                }
            }
        }
        return '没有选择任何海报';
    }
    public function fieldsFormSubmit($rid){
        global $_GPC;
        if (isset($_GPC['channel_id'])){
            yload() -> classs('quicklink', 'channel');
            yload() -> classs('quicklink', 'channelreply');
            $_channel = new Channel();
            $_channelreply = new ChannelReply();
            $record = array('channel' => intval($_GPC['channel_id']), 'rid' => $rid);
            $reply = $_channelreply -> get($rid);
            if ($reply){
                $_channelreply -> update($record, array('id' => $reply['id']));
            }else{
                $_channelreply -> create($record);
            }
        }
    }
    public function ruleDeleted($rid){
        yload() -> classs('quicklink', 'channelreply');
        $_channelreply = new ChannelReply();
        $_channelreply -> remove(array('rid' => $rid));
    }
    public function settingsDisplay($settings){
        global $_GPC, $_W;
        yload() -> classs('quickcenter', 'FormTpl');
        if (checksubmit()){
            if (intval($_GPC['antispam_enable']) == 1){
                if (empty($_GPC['antispam_admin']) or empty($_GPC['antispam_passwd'])){
                    message('您选择了启用报警，必须填写接受报警的管理员的OpenID和移动端拉黑密码', referer(), 'error');
                }
            }
            $cfg = array('notify_leader_follow_text' => $_GPC['notify_leader_follow_text'], 'notify_uplevel_follow_text' => $_GPC['notify_uplevel_follow_text'], 'notify_leader_scan_text' => $_GPC['notify_leader_scan_text'], 'antispam_enable' => intval($_GPC['antispam_enable']), 'antispam_time_threshold' => $_GPC['antispam_time_threshold'], 'antispam_user_threshold' => $_GPC['antispam_user_threshold'], 'antispam_admin' => $_GPC['antispam_admin'], 'antispam_passwd' => $_GPC['antispam_passwd'], 'top_cnt' => intval($_GPC['top_cnt']), 'autoreply_rid' => intval($_GPC['autoreply_rid']),);
            if ($this -> saveSettings($cfg)){
                message('保存成功', 'refresh');
            }
        }
        if (empty($settings['antispam_time_threshold'])){
            $settings['antispam_time_threshold'] = 300;
        }
        if (empty($settings['antispam_user_threshold'])){
            $settings['antispam_user_threshold'] = 20;
        }
        if (empty($settings['top_cnt'])){
            $settings['top_cnt'] = 20;
        }
        yload() -> classs('quicklink', 'channelreply');
        $_channelreply = new ChannelReply();
        $key_res = $_channelreply -> getAllKeyword($_W['weid']);
        $choose_keyword[0] = '首次购买后不自动推送二维码';
        foreach ($key_res as $data){
            $choose_keyword[$data['rid']] = $data['content'];
        }
        include $this -> template('setting');
    }
}
