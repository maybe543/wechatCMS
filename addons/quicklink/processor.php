<?php
defined('IN_IA') or exit('Access Denied');
require IA_ROOT . '/addons/quicklink/define.php';
require MODULE_ROOT . '/quickcenter/loader.php';
class QuickLinkModuleProcessor extends WeModuleProcessor{
    private static $t_reply = 'quickspread_reply';
    public function respond(){
        global $_W;
        WeUtility :: logging('respond1');
        $fans = $this -> refreshUserInfo($this -> message['from']);
        $rule = $this -> message['content'];
        $resp = null;
        WeUtility :: logging("Processor:SUBSCRIBE", $this -> message);
        if ($this -> message['msgtype'] == 'text'){
            WeUtility :: logging('respond2');
            $resp = $this -> respondText($fans, $rule);
        }else if ($this -> message['msgtype'] == 'event' and $this -> message['event'] == 'CLICK'){
            $resp = $this -> respondText($fans, $rule);
        }else if ($this -> message['msgtype'] == 'event'){
            if ($this -> message['event'] == 'subscribe' && 0 === strpos($this -> message['eventkey'], 'qrscene_')){
                $resp = $this -> respondSubscribe($fans, $rule);
            }elseif ($this -> message['event'] == 'SCAN'){
                $resp = $this -> respondScan($fans, $rule);
            }else if ($this -> message['event'] == 'unsubscribe'){
                return null;
            }
        }
        return $resp;
    }
    private function respondText($fans, $rule){
        global $_W;
        $reply = pdo_fetch("SELECT * FROM " . tablename(self :: $t_reply) . " WHERE rid = :rid LIMIT 1", array(':rid' => $this -> rule));
        WeUtility :: logging("Reply", json_encode($reply) . json_encode($this -> rule));
        if (!empty($reply)){
            WeUtility :: logging("Going Running task", $url . "==>" . json_encode($ret));
            yload() -> classs('quickcenter', 'wechatutil');
            $url = WechatUtil :: createMobileUrl('RunTask', $this -> modulename, array('from_user' => $this -> message['from'], 'channel_id' => $reply['channel'], 'rule' => $rule));
            $ret = $this -> http_request($url, 30);
            WeUtility :: logging("Running task", $url . "==>" . json_encode($ret));
        }
        return $this -> responseEmptyMsg();
    }
    private function http_request($url, $timeout = 30){
        $parsed = parse_url($url);
        $host = $parsed['host'];
        $path = $parsed['path'] . '?' . $parsed['query'] ;
        $cookie = '';
        $fp = fsockopen($host, 80, $errno, $errstr, $timeout);
        WeUtility :: logging('fsockopen', array($url, $errno, $errstr, $fp));
        if (!$fp){
            return -1;
        }
        $out = "GET " . $path . " HTTP/1.1\r\n";
        $out .= "Host: " . $host . "\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Cookie: " . $cookie . "\r\n\r\n";
        if (FALSE === fwrite($fp, $out)){
            WeUtility :: logging('write to socket failed', $fp);
        }else{
            fgets($fp, 64);
        }
        fclose($fp);
        WeUtility :: logging('Msg loop thread start success', $fp);
    }
    private function responseEmptyMsg(){
        ob_clean();
        ob_start();
        echo '';
        ob_flush();
        ob_end_flush();
        exit(0);
    }
    private function refreshUserInfo($from_user){
        global $_W;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $force = true;
        $userInfo = $_fans -> refresh($_W['weid'], $from_user, $force);
        WeUtility :: logging('refresh', $userInfo);
        return $userInfo;
    }
    private function respondScan($fans, $rule){
        global $_W;
        yload() -> classs('quicklink', 'scene');
        yload() -> classs('quicklink', 'channel');
        $_scene = new Scene();
        $_channel = new Channel();
        $scene_id = $this -> message['eventkey'];
        WeUtility :: logging('respondScan', $scene_id);
        if (empty($scene_id)){
            WeUtility :: logging('subscribe', 'no scene id');
            return;
        }
        $qr = $_scene -> getQRByScene($_W['weid'], $scene_id);
        if (empty($qr)){
            WeUtility :: logging('subscribe', 'no qr' . $scene_id);
            return;
        }
        WeUtility :: logging('subscribe', $qr);
        $channel = $_channel -> get($_W['weid'], $qr['channel']);
        if (empty($channel)){
            WeUtility :: logging('subscribe', 'no channel');
            return;
        }
        if (empty($channel['title'])){
            WeUtility :: logging('subscribe', 'no channel title');
            return;
        }
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickcenter', 'fans');
        yload() -> classs('quickcenter', 'textparser');
        yload() -> classs('quicklink', 'follow');
        $_weapi = new WechatAPI();
        $_fans = new Fans();
        $_parser = new TextParser();
        $_follow = new Follow();
        $follower = $fans['from_user'];
        $leaderid = $qr['from_user'];
        $leader = $_fans -> get($_W['weid'], $leaderid);
        if ($_follow -> isNewUser($_W['weid'], $follower)){
            WeUtility :: logging('record followship', $qr);
            $_follow -> processSubscribe($_W['weid'], $leaderid, $follower, $qr['channel']);
            $this -> notifyUpLevelFollow($_weapi, $_follow, $_fans, $_W['weid'], $leaderid, $channel);
            $this -> notifyLeaderFollow($_weapi, $_fans, $_W['weid'], $leaderid, $follower, $channel);
        }
        $this -> notifyLeaderScan($_weapi, $_fans, $_W['weid'], $leaderid, $follower, $channel);
        $response = array();
        $channel['title'] = $_parser -> parseScanQRResponse($fans, $leader, $channel['title']);
        $channel['desc'] = $_parser -> parseScanQRResponse($fans, $leader, $channel['desc']);
        if (empty($channel['thumb'])){
            return $this -> respText($channel['desc']);
        }
        $response[] = array('title' => $channel['title'], 'description' => htmlspecialchars_decode($channel['desc']), 'picurl' => $_W['attachurl'] . $channel['thumb'], 'url' => $this -> buildSiteUrl($channel['url']));
        return $this -> respNews($response);
    }
    private function respondSubscribe($fans, $rule){
        global $_W;
        $follower = $fans['from_user'];
        $scene_id = $this -> message['scene'];
        if (empty($scene_id)){
            return;
        }
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quicklink', 'scene');
        yload() -> classs('quicklink', 'channel');
        yload() -> classs('quicklink', 'follow');
        yload() -> classs('quickcenter', 'fans');
        $_scene = new Scene();
        $_channel = new Channel();
        $_follow = new Follow();
        $_weapi = new WechatAPI();
        $_fans = new Fans();
        $qr = $_scene -> getQRByScene($_W['weid'], $scene_id);
        if (empty($qr)){
            WeUtility :: logging('subscribe', 'qr not found using scene ' . $scene_id);
            return;
        }
        $leaderid = $qr['from_user'];
        $leader = $_fans -> get($_W['weid'], $leaderid);
        $channel = $_channel -> get($_W['weid'], $qr['channel']);
        if (empty($channel)){
            WeUtility :: logging('subscribe', 'channel not found using channel ' . $qr['channel']);
            return;
        }
        if ($_follow -> isNewUser($_W['weid'], $follower)){
            WeUtility :: logging('record followship', $qr);
            $_follow -> processSubscribe($_W['weid'], $leaderid, $follower, $qr['channel']);
            $this -> notifyUpLevelFollow($_weapi, $_follow, $_fans, $_W['weid'], $leaderid, $channel);
            $this -> notifyLeaderFollow($_weapi, $_fans, $_W['weid'], $leaderid, $follower, $channel);
        }
        $this -> notifyLeaderScan($_weapi, $_fans, $_W['weid'], $qr['from_user'], $fans['from_user'], $channel);
        yload() -> classs('quickcenter', 'textparser');
        $_parser = new TextParser();
        $channel['title'] = $_parser -> parseScanQRResponse($fans, $leader, $channel['title']);
        $channel['desc'] = $_parser -> parseScanQRResponse($fans, $leader, $channel['desc']);
        if (empty($channel['thumb'])){
            return $this -> respText($channel['desc']);
        }
        $response = array();
        $response[] = array('title' => $channel['title'], 'description' => htmlspecialchars_decode($channel['desc']), 'picurl' => $_W['attachurl'] . $channel['thumb'], 'url' => $this -> buildSiteUrl($channel['url']));
        return $this -> respNews($response);
    }
    private function notifyLeaderFollow($_weapi, $_fans, $weid, $leader, $follower, $channel){
        $t = trim($channel['notify_leader_follow_text']);
        if ('*' == $t){
            return;
        }
        if (!empty($leader)){
            $follower_fans = $_fans -> fans_search_by_openid($weid, $follower, array('nickname'));
            yload() -> classs('quickcenter', 'textparser');
            $_parser = new TextParser();
            $text = $_parser -> parse($follower_fans, $channel['notify_leader_follow_text']);
            $_weapi -> sendText($leader, $text);
            WeUtility :: logging('notifyLeaderFollow', array($leader, $text));
        }
    }
    private function notifyUpLevelFollow($_weapi, $_follow, $_fans, $weid, $this_level_openid, $channel){
        global $_W;
        $t = trim($channel['notify_uplevel_follow_text']);
        if ('*' == $t){
            return;
        }
        $uplevel = $_follow -> getUpLevel($weid, $this_level_openid);
        if (!empty($uplevel)){
            $fans = $_fans -> fans_search_by_openid($weid, $this_level_openid, array('nickname'));
            yload() -> classs('quickcenter', 'textparser');
            $_parser = new TextParser();
            $text = $_parser -> parse($fans, $channel['notify_uplevel_follow_text']);
            $_weapi -> sendText($uplevel['leader'], $text);
            WeUtility :: logging('notifyLeaderFollow', array($uplevel['leader'], $text));
        }
    }
    private function notifyLeaderScan($_weapi, $_fans, $weid, $leader, $follower, $channel){
        $t = trim($channel['notify_leader_scan_text']);
        if ('*' == $t){
            return;
        }
        if (!empty($leader)){
            $follower_fans = $_fans -> fans_search_by_openid($weid, $follower, array('nickname'));
            yload() -> classs('quickcenter', 'textparser');
            $_parser = new TextParser();
            $text = $_parser -> parse($follower_fans, $channel['notify_leader_scan_text']);
            $_weapi -> sendText($leader, $text);
            WeUtility :: logging("notifyLeaderScan", array('leader' => $leader, 'text' => $text));
        }
    }
}
