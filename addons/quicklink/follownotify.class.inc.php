<?php
 class FollowNotify{
    public function notifyLeader($weid, $this_level_openid, $nickname){
        yload() -> classs('quickcenter', 'fans');
        yload() -> classs('quicklink', 'follow');
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $_weapi = new WechatAPI();
        $_follow = new Follow();
        $_fans = new Fans();
        $uplevel = $_follow -> getUpLevel($weid, $this_level_openid);
        if (!empty($uplevel)){
            $fans = $_fans -> fans_search_by_openid($weid, $this_level_openid, array('nickname'));
            $text = '通过分享获得一个新的下线:' . $nickname . ' ' . date('Y-m-d H:i', TIMESTAMP);
            $ch = $_channel -> getActive($weid);
            if (!empty($ch)){
                yload() -> classs('quickcenter', 'textparser');
                $_parser = new TextParser();
                $text = $_parser -> parse($fans, $ch['notify_leader_follow_text']);
            }
            if (!empty($text)){
                $_weapi -> sendText($uplevel['leader'], $text);
                WeUtility :: logging('follow notifyLeaderFollow', array($uplevel['leader'], $text));
            }
        }
    }
    public function notifyLeader2($weid, $this_level_openid, $nickname){
        yload() -> classs('quicklink', 'follow');
        yload() -> classs('quickcenter', 'wechatapi');
        yload() -> classs('quickcenter', 'fans');
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $_follow = new Follow();
        $_weapi = new WechatAPI();
        $_fans = new Fans();
        $up = $_follow -> getUpLevel($weid, $this_level_openid);
        $uplevel = $_follow -> getUpLevel($weid, $up['leader']);
        if (!empty($uplevel)){
            $text = '获得一个新的2级下线:' . $nickname . ' ' . date('Y-m-d H:i', TIMESTAMP);
            $ch = $_channel -> getActive($weid);
            if (!empty($ch)){
                yload() -> classs('quickcenter', 'textparser');
                $_parser = new TextParser();
                $text = $_parser -> parse($fans, $ch['notify_uplevel_follow_text']);
            }
            if (!empty($text)){
                $_weapi -> sendText($uplevel['leader'], $text);
                WeUtility :: logging('notifyLeaderFollow2', array($uplevel['leader'], $text));
            }
        }
    }
    public function notifyFollower($weid, $this_level_openid, $nickname){
        yload() -> classs('quicklink', 'follow');
        yload() -> classs('quickcenter', 'wechatapi');
        $_follow = new Follow();
        $_weapi = new WechatAPI();
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        if (!empty($this_level_openid)){
            $text = 'Hi ' . $nickname . '，欢迎光临本店';
            $_weapi -> sendText($this_level_openid, $text);
        }
    }
}
