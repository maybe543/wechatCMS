<?php
 class TransLink{
    private static $t_textlink = 'quickspread_textlink';
    public function preLink($weid, $shareby){
        if (!empty($shareby)){
            $key = 'shareby' . $weid;
            setcookie($key, $shareby, TIMESTAMP + 3600 * 24);
        }
    }
    public function link($weid, $fansInfo){
        $key = 'shareby' . $weid;
        if (isset($_COOKIE[$key]) && !empty($_COOKIE[$key]) && !empty($fansInfo)){
            yload() -> classs('quicklink', 'follow');
            $_follow = new Follow();
            if ($_follow -> isNewUser($weid, $fansInfo['from_user'])){
                $ch = $this -> getTextLinkChannel($weid);
                $ret = $_follow -> recordFollow($weid, $_COOKIE[$key], $fansInfo['from_user'], -1, $ch['click_credit'], $ch['sub_click_credit'], $ch['newbie_credit']);
                $this -> afterLink($weid, $ch, $fansInfo);
            }
            $this -> cleanLink($weid);
        }
        return;
    }
    private function afterLink($weid, $ch, $fans){
        yload() -> classs('quicklink', 'follownotify');
        $_notifier = new FollowNotify();
        $_notifier -> notifyFollower($weid, $fans['from_user'], $fans['nickname']);
        $_notifier -> notifyLeader($weid, $fans['from_user'], $fans['nickname']);
        $_notifier -> notifyLeader2($weid, $fans['from_user'], $fans['nickname']);
        WeUtility :: logging('All Notified', $weid);
    }
    public function cleanLink($weid){
        setcookie("shareby" . $weid, "system", time() - 3600);
    }
    public function getTextLinkChannel($weid){
        yload() -> classs('quicklink', 'channel');
        $_channel = new Channel();
        $ret = $_channel -> getActive($weid);
        return $ret;
    }
}
?>
