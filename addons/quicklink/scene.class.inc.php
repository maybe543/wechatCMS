<?php
 class Scene{
    private static $t_sys_qr = 'qrcode';
    private static $t_qr = 'quickspread_qr';
    private static $t_scene_id = 'quickspread_scene_id';
    private static $WECHAT_MEDIA_EXPIRE_SEC = 255600;
    public function getNextAvaliableSceneID($weid){
        $scene_id = pdo_fetchcolumn('SELECT scene_id FROM ' . tablename(self :: $t_scene_id) . ' WHERE weid=:weid', array(':weid' => $weid));
        if (empty($scene_id)){
            $scene_id = 200;
            WeUtility :: logging('sc emtpy', $scene_id);
            pdo_insert(self :: $t_scene_id, array('weid' => $weid, 'scene_id' => $scene_id));
        }else{
            $scene_id++;
            pdo_update(self :: $t_scene_id, array('scene_id' => $scene_id), array('weid' => $weid));
        }
        return $scene_id;
    }
    public function getQR($weid, $from_user, $channel){
        $qr = pdo_fetch("SELECT * FROM " . tablename(self :: $t_qr) . " WHERE from_user=:uid AND channel=:channel AND from_user=:from_user AND weid=:weid " . " ORDER BY createtime DESC LIMIT 1", array(":uid" => $from_user, ":channel" => $channel, ":from_user" => $from_user, ":weid" => $weid));
        if (!empty($qr) and $qr['createtime'] + self :: $WECHAT_MEDIA_EXPIRE_SEC < time()){
        }
        return $qr;
    }
    public function getQRByScene($weid, $scene_id){
        $qr = pdo_fetch("SELECT * FROM " . tablename(self :: $t_qr) . " WHERE scene_id=:scene_id AND weid=:weid", array(":scene_id" => $scene_id, ":weid" => $weid));
        return $qr;
    }
    public function newQR($weid, $from_user, $scene_id, $qr_url, $media_id, $channel, $keyword){
        $params = array("weid" => $weid, "from_user" => $from_user, "scene_id" => $scene_id, "qr_url" => $qr_url, "media_id" => $media_id, "channel" => $channel, "createtime" => time());
        $sys_params = array("uniacid" => $weid, "qrcid" => $scene_id, "model" => 2, "name" => $from_user, "keyword" => $keyword, "expire" => 0, "createtime" => time(), "status" => 1, "ticket" => $media_id);
        $ret = pdo_insert(self :: $t_qr, $params);
        $ret = pdo_insert(self :: $t_sys_qr, $sys_params);
        if (!empty($ret)){
        }
        return $ret;
    }
    public function updateQR($weid, $from_user, $scene_id, $qr_url, $media_id, $channel){
        $ret = pdo_update(self :: $t_qr, array("scene_id" => $scene_id, "qr_url" => $qr_url, "media_id" => $media_id, "channel" => $channel), array("from_user" => $from_user, "weid" => $weid));
        return $ret;
    }
}
