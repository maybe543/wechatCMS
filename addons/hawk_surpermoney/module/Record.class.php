<?php
class Record{
    private $Table_record = 'hmoney_records';
    public function create($entity){
        global $_W;
        $input = array();
        $input['uniacid'] = $_W['uniacid'];
        $input['openid']  = $entity['openid'];
        $input['articleid'] = $entity['articleid'];
        $input['createtime'] = TIMESTAMP;
        $res = pdo_insert($this->Table_record,$input);
        if($res){
            $id = pdo_insertid();
            return $id;
        }else{
            return false;
        }
    }
    public function getOne($openid,$articleid='',$filters=array()){
        global $_W;
        $pars = array();
        $condition = '`uniacid`=:uniacid AND `openid`=:openid  ';
        if(!empty($articleid)){
            $pars[':articleid'] = $articleid;
            $condition .= ' AND `articleid`=:articleid';
        }
        if(!empty($filters)){
            $time = time();
            if($filters['time']=='today'){
                $start = strtotime(date("Y-m-d",$time));
                $end   = $start + 86400;
            }elseif($filters['time']=='yestoday'){
                $start = strtotime(date("Y-m-d",$time)) - 86400;
                $end   = strtotime(date("Y-m-d",$time));
            }
            $condition .= ' AND `createtime` between '.$start.' AND '.$end ;

        }
        //echo $condition;
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;

        $sql = 'SELECT * FROM ' . tablename($this->Table_record) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            return $entity;
        }else{
            return false;
        }
    }

    public function getAll($id){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `articleid`=:articleid ';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':articleid'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->Table_record) . " WHERE {$condition}";
        $res = pdo_fetchall($sql,$pars);
        if($res){
            return $res;
        }
        return false;
    }
}