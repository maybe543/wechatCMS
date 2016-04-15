<?php
 class Follow{
    static $t_sys_fans = 'mc_mapping_fans';
    static $t_sys_member = 'mc_members';
    private static $t_local_fans = 'quickspread_fans';
    private static $t_black = 'quickspread_blacklist';
    private static $t_credit = 'quickspread_credit';
    private static $t_follow = 'quickspread_follow';
    private static $t_channel = 'quickspread_channel';
    public function processSubscribe($weid, $leader_uid, $follower_uid, $channel){
        $this -> addFollow($weid, $leader_uid, $follower_uid, $channel);
    }
    public function getFollowCountByChannel($weid, $channel){
        $ret = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self :: $t_follow) . " WHERE channel=:channel AND weid=:weid", array(":channel" => $channel, ":weid" => $weid));
        return $ret;
    }
    public function getAllChannelFollowCount($weid){
        $ret = pdo_fetchall("SELECT channel, COUNT(*) cnt FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid GROUP BY channel", array(":weid" => $weid), "channel");
        return $ret;
    }
    private function isLeader($weid, $leader){
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE leader=:leader AND weid=:weid", array(":leader" => $leader, ":weid" => $weid));
        return $ret;
    }
    private function isFollower($weid, $follower){
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE follower=:follower AND weid=:weid", array(":follower" => $follower, ":weid" => $weid));
        return $ret;
    }
    private function addSysCredit($weid, $from_user, $credit, $tag){
        global $_W;
        yload() -> classs('quickcenter', 'fans');
        $_fans = new Fans();
        $ret = $_fans -> addCredit($weid, $from_user, $credit, 1, $tag);
        return $ret;
    }
    private function addLocalCredit($weid, $from_user, $credit, $type){
        $ret = pdo_insert(self :: $t_credit, array('weid' => $weid, 'from_user' => $from_user, 'type' => $type, 'credit' => $credit, 'createtime' => time()));
        return $ret;
    }
    public function addFollow($weid, $leader_uid, $follower_uid, $channel){
        WeUtility :: logging('addFollow param', array($weid, $leader_uid, $follower_uid, $channel));
        $ch = pdo_fetch('SELECT sub_click_credit, click_credit, newbie_credit FROM ' . tablename(self :: $t_channel) . ' WHERE channel=:channel AND weid=:weid', array(':weid' => $weid, ':channel' => $channel));
        if (empty($ch)){
            WeUtility :: logging('addFollow fail, invalid channel', $channel);
        }
        $ret = $this -> recordFollow($weid, $leader_uid, $follower_uid, $channel, $ch['click_credit'], $ch['sub_click_credit'], $ch['newbie_credit']);
        return $ret;
    }
    public function unFollow($weid, $leader_uid, $follower_uid){
        WeUtility :: logging('unFollow param', array($weid, $leader_uid, $follower_uid));
        $ret = pdo_delete(self :: $t_follow, array('weid' => $weid, 'leader' => $leader_uid, 'follower' => $follower_uid));
        return $ret;
    }
    public function changeFollow($weid, $old_leader_uid, $new_leader_uid, $follower_uid){
        $ret = 0;
        $channel = -1;
        $click_credit = 0;
        $hasLoop = $this -> checkLoop($weid, $old_leader_uid, $new_leader_uid, $follower_uid);
        if (!$hasLoop){
            if (empty($old_leader_uid)){
                $ret = pdo_insert(self :: $t_follow, array('weid' => $weid, 'leader' => $new_leader_uid, 'follower' => $follower_uid, 'channel' => $channel, 'credit' => $click_credit, 'createtime' => time()));
            }else if ($old_leader_uid != $new_leader_uid){
                $ret = pdo_update(self :: $t_follow, array('leader' => $new_leader_uid, 'createtime' => time()), array('weid' => $weid, 'leader' => $old_leader_uid, 'follower' => $follower_uid));
            }
        }
        return $ret;
    }
    private function checkLoop($weid, $old_leader_uid, $new_leader_uid, $follower_uid){
        if ($new_leader_uid == $follower_uid){
            return true;
        }
        $hasLoop = true;
        $loopLimit = 20;
        $this_level_openid = $new_leader_uid;
        while ($loopLimit-- > 0){
            $level = $this -> getUpLevel($weid, $this_level_openid);
            if (empty($level)){
                $hasLoop = false;
                break;
            }elseif ($level['leader'] == $follower_uid){
                break;
            }else{
                $this_level_openid = $level['leader'];
            }
        }
        return $hasLoop;
    }
    public function recordFollow($weid, $leader_uid, $follower_uid, $channel, $click_credit, $sub_click_credit, $newbie_credit){
        if ($leader_uid != $follower_uid){
            yload() -> classs('quicklink', 'antispam');
            $_spam = new AntiSpam();
            $_spam -> filter($weid, $leader_uid, $follower_uid);
            if ($this -> isNewUser($weid, $follower_uid)){
                $ret = $this -> addLocalCredit($weid, $follower_uid, $newbie_credit, '首次关注积分');
                $ret = $this -> addSysCredit($weid, $follower_uid, $newbie_credit, '首次关注送积分');
                $ret = $this -> addNewUser($weid, $follower_uid);
            }else{
            }
            $ret = pdo_insert(self :: $t_follow, array('weid' => $weid, 'leader' => $leader_uid, 'follower' => $follower_uid, 'channel' => $channel, 'credit' => $click_credit, 'createtime' => time()));
            if (!$this -> inBlackList($weid, $leader_uid)){
                $ret = $this -> addLocalCredit($weid, $leader_uid, $click_credit, '直接推广积分');
                $ret = $this -> addSysCredit($weid, $leader_uid, $click_credit, '获得一个新下线送积分');
            }
            $uplevel = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND follower=:follower", array(":weid" => $weid, ":follower" => $leader_uid));
            if (!empty($uplevel)){
                if (!$this -> inBlackList($weid, $uplevel['leader'])){
                    $ret = $this -> addLocalCredit($weid, $uplevel['leader'], $sub_click_credit, '间接推广积分');
                    $ret = $this -> addSysCredit($weid, $uplevel['leader'], $sub_click_credit, '下线获得一个新关注送积分');
                }
            }
        }
    }
    private function inBlackList($weid, $from_user){
        global $_W;
        $b = pdo_fetch("SELECT * FROM " . tablename(self :: $t_black) . " WHERE from_user=:f AND weid=:w LIMIT 1", array(':f' => $from_user, ':w' => $weid));
        if (!empty($b)){
            $hit = 1 + $b['hit'];
            pdo_update(self :: $t_black, array('hit' => $hit), array('from_user' => $from_user, 'weid' => $weid));
        }
        return $b;
    }
    public function disappear($weid, $from_user){
        $fans = pdo_fetch("SELECT * FROM " . tablename(self :: $t_sys_fans) . " WHERE openid=:openid AND uniacid=:uniacid LIMIT 1", array(':openid' => $from_user, ':uniacid' => $weid));
        if (!empty($fans)){
            $ret = pdo_delete(self :: $t_sys_fans, array("openid" => $from_user, "uniacid" => $weid));
            $ret = pdo_delete(self :: $t_sys_member, array("uid" => $fans['uid'], "uniacid" => $weid));
        }
        $ret = pdo_delete(self :: $t_local_fans, array("from_user" => $from_user, "weid" => $weid));
        $ret = pdo_delete(self :: $t_follow, array("follower" => $from_user, "weid" => $weid));
        $ret = pdo_delete(self :: $t_follow, array("leader" => $from_user, "weid" => $weid));
    }
    public function isNewUser($weid, $from_user){
        global $_W;
        WeUtility :: logging('IsNewUser input:', array($weid, $from_user));
        $ret = pdo_fetch("SELECT * FROM " . tablename(self :: $t_local_fans) . " WHERE  from_user=:from_user AND weid=:weid LIMIT 1", array(":from_user" => $from_user, ":weid" => $weid));
        WeUtility :: logging('isLocalFans', $ret);
        if (empty($ret)){
            $ret = $this -> isLeader($weid, $from_user);;
            WeUtility :: logging('isLeader', $ret);
        }
        if (empty($ret)){
            $ret = $this -> isFollower($weid, $from_user);;
            WeUtility :: logging('isFollower', $ret);
        }
        WeUtility :: logging("isNewUser output", $ret);
        return empty($ret);
    }
    public function addNewUser($weid, $from_user){
        $ret = pdo_insert(self :: $t_local_fans, array('weid' => $weid, 'from_user' => $from_user, 'createtime' => time()));
        return $ret;
    }
    public function getUpLevel($weid, $this_level_openid){
        $uplevel = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND follower=:follower LIMIT 1", array(":weid" => $weid, ":follower" => $this_level_openid));
        return $uplevel;
    }
}
