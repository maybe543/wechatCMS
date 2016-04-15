<?php
class Fan
{
    private $Table_fans = 'hmoney_fans';

    public function create($entity)
    {
        global $_W;
        $rec = array_elements(array('openid', 'nickname', 'avatar'), $entity);
        if(empty($rec['openid']) || empty($rec['nickname'])){
            return false;
        }
        $rec['uniacid'] = $_W['uniacid'];
        $rec['createtime'] = TIMESTAMP;
        $condition = '`uniacid`=:uniacid AND `openid`=:openid';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':openid'] = $rec['openid'];
        $sql = 'SELECT * FROM ' . tablename($this->Table_fans) . " WHERE {$condition}";
        $exists = pdo_fetch($sql, $pars);
        if (!empty($exists)) {
            return false;
        }
        $ret = pdo_insert($this->Table_fans, $rec);
        if (!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }

    public function modify($openid, $entity) {
        $ret = pdo_update($this->Table_fans,$entity, array('openid'=>$openid));
        if($ret){
            return true;
        }
        return false;
    }

    public function getOne($openid) {
        global $_W;
        $condition = '`uniacid`=:uniacid AND `openid`=:openid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;
        $sql = 'SELECT * FROM ' . tablename($this->Table_fans) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }
        return false;
    }

    public function getAll(){
        global $_W;
        $condition = '`uniacid`=:uniacid ORDER BY credit DESC LIMIT 10' ;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = 'SELECT * FROM ' . tablename($this->Table_fans) . " WHERE {$condition}";
        $entity = pdo_fetchall($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }
        return false;
    }



}