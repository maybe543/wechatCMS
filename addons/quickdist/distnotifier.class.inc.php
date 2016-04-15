<?php
class DistNotifier{
    private static $t_notify = 'quickdist_notify';
    public static function decode_param(& $item, $p){
        $gpc = unserialize($p);
        $item[1] = $gpc[1];
        $item[2] = $gpc[2];
        $item[3] = $gpc[3];
        return $item;
    }
    public static function encode_param($gpc){
        $params = array('1' => $gpc['notify_level_1_text'], '2' => $gpc['notify_level_2_text'], '3' => $gpc['notify_level_3_text'],);
        return serialize($params);
    }
    public function get($weid){
        $msg = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_notify) . ' WHERE weid=:weid LIMIT 1', array(':weid' => $weid));
        if (!empty($msg)){
            $msg = self :: decode_param($msg, $msg['param']);
            unset($msg['param']);
        }
        return $msg;
    }
    public function create($weid, $gpc){
        $param = self :: encode_param($gpc);
        $ret = pdo_insert(self :: $t_notify, array('weid' => $weid, 'param' => $param, 'level' => $gpc['level'],));
        return $ret;
    }
    public function update($weid, $gpc){
        $param = self :: encode_param($gpc);
        $ret = pdo_update(self :: $t_notify, array('param' => $param, 'level' => $gpc['level']), array('weid' => $weid));
        return $ret;
    }
    public function notify($weid, $leader, $leader_level, $from_user, $com_val_acc, $totalprice, $msg_template){
        global $_W;
        if ($com_val_acc <= 0) return;
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickcenter', 'textparser');
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $_api = new WechatAPI();
        $_parser = new TextParser();
        $buyer = $_fans -> get($weid, $from_user);
        $map = array('/\[buyer\]/' => $buyer['nickname'], '/\[level\]/' => $leader_level, '/\[price\]/' => $totalprice, '/\[return\]/' => $com_val_acc, '/\[time\]/' => date('m-d H:i:s', TIMESTAMP));
        $msg = $_parser -> batchParse($map, $msg_template);
        $_api -> sendText($leader, $msg);
    }
}
