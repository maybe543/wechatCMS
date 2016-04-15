<?php
 class Member{
    private static $t_follow = 'quickspread_follow';
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_member = 'mc_members';
    const MAX_LEVEL = 3;
    public function getMemberCountByLevel($weid, $boss_openid, $level, $followonly = false){
        $member = $this -> getMemberInfoByLevel($weid, $boss_openid, $level);
        if (true == $followonly){
            $cnt = 0;
            foreach ($member as $m){
                if ($m['follow'] == 1){
                    $cnt++;
                }
            }
            return $cnt;
        }
        return count($member);
    }
    public function getMemberInfoByLevel($weid, $boss_openid, $level, $pindex = 1, $psize = 9999999){
        $member = array();
        if ($level > self :: MAX_LEVEL){
            return $member;
        }
        $member = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND leader=:boss_openid", array(':weid' => $weid, ':boss_openid' => $boss_openid), 'follower');
        $cur_level = 1;
        while ($level > $cur_level && count($member) > 0){
            $member = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND leader IN ('" . join("','", array_keys($member)) . "')", array(':weid' => $weid), 'follower');
            $cur_level++;
        }
        if (!empty($member)){
            $member = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_sys_fans) . " a LEFT JOIN " . tablename(self :: $t_sys_member) . " b ON a.uid = b.uid AND a.uniacid = b.uniacid WHERE {$WHERE} a.uniacid=:weid AND a.openid IN ('" . join("','", array_keys($member)) . "')", array(':weid' => $weid), 'openid');
        }
        return $member;
    }
    public function getAllLevelMemberInfo($weid, $follower, $max_level){
        $member = array();
        $members = array();
        if ($max_level > self :: MAX_LEVEL){
            $max_level = self :: MAX_LEVEL;
        }
        $cur_level = 1;
        $member['follower'] = $follower;
        while ($max_level >= $cur_level && !empty($member)){
            $member = pdo_fetch("SELECT * FROM " . tablename(self :: $t_follow) . " WHERE weid=:weid AND follower =:follower LIMIT 1", array(':weid' => $weid, ':follower' => $member['follower']), 'follower');
            if (!empty($member)){
                $members[$cur_level] = $member;
            }else{
                break;
            }
            $cur_level++;
            $member['follower'] = $member['leader'];
        }
        return $members;
    }
}
