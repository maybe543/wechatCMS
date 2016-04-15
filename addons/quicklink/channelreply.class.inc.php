<?php
 class ChannelReply{
    private static $t_reply = 'quickspread_reply';
    private static $t_rule_keyword = 'rule_keyword';
    public function get($rid){
        $reply = pdo_fetch("SELECT * FROM " . tablename(self :: $t_reply) . " WHERE rid = :rid", array(':rid' => $rid));
        return $reply;
    }
    public function getKeyword($weid, $channel){
        $keywords = pdo_fetchall("SELECT content FROM " . tablename(self :: $t_reply) . " a JOIN " . tablename(self :: $t_rule_keyword) . " b " . " ON a.rid = b.rid WHERE a.channel = :ch AND b.uniacid= :weid", array(':ch' => $channel, ':weid' => $weid));
        return $keywords;
    }
    public function getAllKeyword($weid, $key = null){
        $keywords = pdo_fetchall("SELECT rid, content FROM " . tablename(self :: $t_rule_keyword) . " WHERE  uniacid= :weid AND module='quicklink' ", array(':weid' => $weid), $key);
        return $keywords;
    }
    public function getKeywordAndChannel($weid, $rid){
        $result = pdo_fetch("SELECT content, channel FROM " . tablename(self :: $t_reply) . " a JOIN " . tablename(self :: $t_rule_keyword) . " b " . " ON a.rid = b.rid WHERE a.rid = :rid AND b.uniacid= :weid", array(':rid' => $rid, ':weid' => $weid));
        return $result;
    }
    public function update($data, $cond){
        $ret = pdo_update(self :: $t_reply, $data, $cond);
        return $ret;
    }
    public function create($data){
        $ret = pdo_insert(self :: $t_reply, $data);
        return $ret;
    }
    public function remove($cond){
        $ret = pdo_delete(self :: $t_reply, $cond);
        return $ret;
    }
}
