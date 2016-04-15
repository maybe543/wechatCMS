<?php
class Channel{
    private static $t_channel = 'quickspread_channel';
    private static $t_active_ch = 'quickspread_active_channel';
    public function get($weid, $channel_id){
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid=:weid AND channel=:channel", array(":weid" => $weid, ":channel" => $channel_id));
        self :: decode_channel_param($ret, $ret['bgparam']);
        return $ret;
    }
    public function create($weid, $active, $gpc){
        $bgparam = self :: encode_channel_param($gpc);
        pdo_insert(self :: $t_channel, array('title' => $gpc['title'], 'createtime' => time(), 'thumb' => $gpc['thumb'], 'bg' => $gpc['bg'], 'bgparam' => $bgparam, 'active' => $active, 'desc' => $gpc['desc'], 'url' => $gpc['url'], 'click_credit' => $gpc['click_credit'], 'sub_click_credit' => $gpc['sub_click_credit'], 'newbie_credit' => $gpc['newbie_credit'], 'weid' => $weid));
        return $ret;
    }
    public function update($weid, $channel_id, $gpc){
        $bgparam = self :: encode_channel_param($gpc);
        $ret = pdo_update(self :: $t_channel, array('createtime' => time(), 'title' => $gpc['title'], 'thumb' => $gpc['thumb'], 'bg' => $gpc['bg'], 'bgparam' => $bgparam, 'desc' => $gpc['desc'], 'url' => $gpc['url'], 'click_credit' => $gpc['click_credit'], 'sub_click_credit' => $gpc['sub_click_credit'], 'newbie_credit' => $gpc['newbie_credit']), array('channel' => $channel_id, 'weid' => $weid));
        return $ret;
    }
    public function remove($weid, $channel_id){
        $ret = pdo_update(self :: $t_channel, array('deleted' => 1), array("channel" => $channel_id, "weid" => $weid));
        return $ret;
    }
    public function batchGet($weid, $key = null){
        $mylist = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid=:weid AND deleted = 0", array(":weid" => $weid), $key);
        return $mylist;
    }
    public function getActive($weid){
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid=:weid AND active=1 AND deleted = 0 LIMIT 1", array(":weid" => $weid));
        self :: decode_channel_param($ret, $ret['bgparam']);
        return $ret;
    }
    public function setActive($weid, $channel_id){
        pdo_update(self :: $t_channel, array('active' => 0), array('weid' => $weid));
        pdo_update(self :: $t_channel, array('active' => 1), array('weid' => $weid, 'channel' => $channel_id));
    }
    public function getAny($weid){
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_channel) . " WHERE weid=:weid AND deleted = 0 LIMIT 1", array(":weid" => $weid));
        self :: decode_channel_param($ret, $ret['bgparam']);
        return $ret;
    }
    public function refresh($weid, $channel_id){
        pdo_update(self :: $t_channel, array('createtime' => time()), array('weid' => $weid, 'channel' => $channel_id));
    }
    public static function decode_channel_param(& $item, $p){
        $gpc = unserialize($p);
        $item['qrquality'] = (intval($gpc['qrquality']) > 0 and intval($gpc['qrquality']) <= 100) ? intval($gpc['qrquality']) : 70;
        $item['qrleft'] = intval($gpc['qrleft']) ? intval($gpc['qrleft']) : 145;
        $item['qrtop'] = intval($gpc['qrtop']) ? intval($gpc['qrtop']) : 475;
        $item['qrwidth'] = intval($gpc['qrwidth']) ? intval($gpc['qrwidth']) : 240;
        $item['qrheight'] = intval($gpc['qrheight']) ? intval($gpc['qrheight']) : 240;
        $item['avatarleft'] = intval($gpc['avatarleft']) ? intval($gpc['avatarleft']) : 40;
        $item['avatartop'] = intval($gpc['avatartop']) ? intval($gpc['avatartop']) : 40;
        $item['avatarwidth'] = intval($gpc['avatarwidth']) ? intval($gpc['avatarwidth']) : 96;
        $item['avatarheight'] = intval($gpc['avatarheight']) ? intval($gpc['avatarheight']) : 96;
        $item['avatarenable'] = intval($gpc['avatarenable']);
        $item['nameleft'] = intval($gpc['nameleft']) ? intval($gpc['nameleft']) : 150;
        $item['nametop'] = intval($gpc['nametop']) ? intval($gpc['nametop']) : 50;
        $item['namesize'] = intval($gpc['namesize']) ? intval($gpc['namesize']) : 30;
        $item['namecolor'] = empty($gpc['namecolor']) ? '#000000' : $gpc['namecolor'];
        $item['nameenable'] = intval($gpc['nameenable']);
        $item['genqr_info1'] = empty($gpc['genqr_info1']) ? '正在为您生成二维码中，请稍候。大约需要等待6秒钟，稍等哦。。' : $gpc['genqr_info1'];
        $item['genqr_info2'] = $gpc['genqr_info2'];
        $item['genqr_info3'] = $gpc['genqr_info3'];
        $item['vip_limit'] = empty($gpc['vip_limit']) ? 0 : intval($gpc['vip_limit']);
        $item['genqr_vip_limit_info'] = empty($gpc['genqr_vip_limit_info']) ? '对不起，您的VIP等级不够，无法参与活动。' : $gpc['genqr_vip_limit_info'];
        $item['bgimages'] = self :: decode_image_array($gpc['bgimages']);
        $item['notify_leader_follow_text'] = $gpc['notify_leader_follow_text'];
        $item['notify_uplevel_follow_text'] = $gpc['notify_uplevel_follow_text'];
        $item['notify_leader_scan_text'] = $gpc['notify_leader_scan_text'];
        if (!empty($item['bg'])){
            $item['bgimages'][] = $item['bg'];
        }
        return $item;
    }
    public static function encode_channel_param($gpc){
        $params = array('qrquality' => intval($gpc['qrquality']), 'qrleft' => intval($gpc['qrleft']), 'qrtop' => intval($gpc['qrtop']), 'qrwidth' => intval($gpc['qrwidth']), 'qrheight' => intval($gpc['qrheight']), 'avatarleft' => intval($gpc['avatarleft']), 'avatartop' => intval($gpc['avatartop']), 'avatarwidth' => intval($gpc['avatarwidth']), 'avatarheight' => intval($gpc['avatarheight']), 'avatarenable' => intval($gpc['avatarenable']), 'nameleft' => intval($gpc['nameleft']), 'nametop' => intval($gpc['nametop']), 'namesize' => intval($gpc['namesize']), 'namecolor' => $gpc['namecolor'], 'nameenable' => intval($gpc['nameenable']), 'genqr_info1' => $gpc['genqr_info1'], 'genqr_info2' => $gpc['genqr_info2'], 'genqr_info3' => $gpc['genqr_info3'], 'genqr_vip_limit_info' => $gpc['genqr_vip_limit_info'], 'vip_limit' => $gpc['vip_limit'], 'bgimages' => self :: encode_image_array($gpc), 'notify_leader_follow_text' => $gpc['notify_leader_follow_text'], 'notify_uplevel_follow_text' => $gpc['notify_uplevel_follow_text'], 'notify_leader_scan_text' => $gpc['notify_leader_scan_text'],);
        return serialize($params);
    }
    private static function encode_image_array($gpc){
        if (!empty($gpc['bgimages'])){
            foreach ($gpc['bgimages'] as $row){
                if (empty($row)){
                    continue;
                }
                $hsdata[] = $row;
            }
        }
        $serialized_urls = serialize($hsdata);
        return $serialized_urls;
    }
    private static function decode_image_array($dburls){
        $deserialized_urls = unserialize($dburls);
        if (empty($deserialized_urls)){
            $deserialized_urls = array();
        }
        return $deserialized_urls;
    }
}
