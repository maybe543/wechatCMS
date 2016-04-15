<?php
 class TopFollow{
    private static $t_cache = 'quickspread_top_cache';
    private static $t_follow = 'quickspread_follow';
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_member = 'mc_members';
    public function updateCache($weid, $list){
        $ret = pdo_query('REPLACE INTO ' . tablename(self :: $t_cache) . ' (weid, createtime, cache) VALUES (:weid, :ct, :cache)', array(':weid' => $weid, ':ct' => time(), ':cache' => serialize($list)));
        return $ret;
    }
    public function getCached($weid){
        $cache = pdo_fetch('SELECT cache FROM ' . tablename(self :: $t_cache) . ' WHERE weid=:weid', array(':weid' => $weid));
        if (!empty($cache)) $result = unserialize($cache['cache']);
        return $result;
    }
    public function get($weid, $psize = 20){
        $pindex = 1;
        $orderby = ' follower_credit';
        $having = ' HAVING follower_count > 0 ';
        $my_follows_sql = "SELECT a.followtime createtime, a.openid from_user, COUNT(b.follower) follower_count, SUM(b.credit) follower_credit, c.credit1,c.nickname, c.avatar FROM  " . tablename(self :: $t_sys_fans) . " a LEFT JOIN " . tablename(self :: $t_follow) . " b ON a.openid= b.leader AND a.uniacid=b.weid  LEFT JOIN " . tablename(self :: $t_sys_member) . " c  ON a.uid=c.uid " . " WHERE a.uniacid=:weid GROUP BY a.openid " . $having . " ORDER BY " . $orderby . " DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize;
        $mylist = pdo_fetchall($my_follows_sql, array(':weid' => $weid));
        return $mylist;
    }
}
