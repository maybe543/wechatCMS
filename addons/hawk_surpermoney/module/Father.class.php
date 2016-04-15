<?php
class Father{
    private $Table_father = 'hmoney_father';
    private $Table_fans   = 'mc_mapping_fans';
    public function getOne($openid){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `openid`=:openid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;
        $sql = 'SELECT * FROM ' . tablename($this->Table_father) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }else{
            return false;
        }
    }
    public function getAll($openid){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `father`=:father ORDER BY `id` DESC';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':father'] = $openid;
        $sql = 'SELECT * FROM ' . tablename($this->Table_father) . " WHERE {$condition}";
        $entity = pdo_fetchall($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }else{
            return false;
        }
    }
    public function getfan($openid){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `openid`=:openid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;
        $sql = 'SELECT * FROM ' . tablename($this->Table_fans) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }else{
            return false;
        }
    }
    public function create($openid,$from){
        global $_W;
        $input = array();
        $input['uniacid']= $_W['uniacid'];
        $input['openid'] = $openid;
        $input['father'] = $from;
        $input['createtime'] = TIMESTAMP;
        $res = pdo_insert($this->Table_father,$input);
        if(!empty($res)){
            $id = pdo_insertid();
            return $id;
        }else{
            return false;
        }

    }
    public function modify($openid,$entity) {
        global $_W;
        $pars = array();
        $pars['uniacid'] = $_W['uniacid'];
        $pars['openid'] = $openid;
        $exists = $this->getOne($openid);
        if(!empty($exists)) {
            $ret = pdo_update($this->Table_father,$entity,$pars);
            if($ret){
                return true;
            }else{
                return false;
            }
        }

    }
}