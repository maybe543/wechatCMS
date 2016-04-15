<?php
 class TopCredit{
    private static $t_sys_fans = 'mc_mapping_fans';
    private static $t_sys_member = 'mc_members';
    public function get($weid, $limit = 20){
        $mylist = pdo_fetchall('SELECT * FROM ' . tablename(self :: $t_sys_fans) . ' a LEFT JOIN ' . tablename(self :: $t_sys_member) . ' b ON a.uid=b.uid ' . ' WHERE a.uniacid=:uniacid AND follow=1 ORDER BY credit1 DESC LIMIT ' . $limit, array(':uniacid' => $weid));
        return $mylist;
    }
}
