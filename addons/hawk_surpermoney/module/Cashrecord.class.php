<?php
class Cashrecord{
    private $Table_record = 'hmoney_cashrecords';
    public function create($entity){
        global $_W;
        $input = array();
        $input['uniacid'] = $_W['uniacid'];
        $input['openid']  = $entity['openid'];
        $input['money'] = $entity['money'];
        $input['createtime'] = TIMESTAMP;
        $res = pdo_insert($this->Table_record,$input);
        if($res){
            $id = pdo_insertid();
            return $id;
        }else{
            return false;
        }
    }
    public function getmaxid() {
        $condition = " ORDER BY id DESC ";
        $sql = "SELECT id  FROM " . tablename($this->Table_record)." {$condition}";
        $rs = pdo_fetch($sql);
        if(empty($rs['id'])) {
            return 1;
        }else{
            return $rs['id'] + 1;
        }
    }

    public function getAll(){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `openid`=:openid AND `money` > 0  ORDER BY id DESC' ;
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $_W['fans']['from_user'];
        $sql = 'SELECT * FROM ' . tablename($this->Table_record) . " WHERE {$condition}";
        $entity = pdo_fetchall($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }
        return false;
    }

}