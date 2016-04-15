<?php
class Activity{
    private $Table_activity = 'hticket_activity';
    private $Table_order    = 'hticket_order';
    private $Table_log    = 'hticket_log';
    public function create($entity){
        global $_W;
        $rec = array_elements(array('title','description','shareimg','content','singleimg','status','extype','starttime','endtime','place','proname','tlimit','scantimes','fee','author','groups','viewnums'), $entity);
        $rec['uniacid'] = $_W['uniacid'];
        $rec['createtime'] = TIMESTAMP;
        $condition = '`uniacid`=:uniacid AND `title`=:title';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':title'] = $rec['title'];
        $sql = 'SELECT * FROM ' . tablename($this->Table_activity) . " WHERE {$condition}";
        $exists = pdo_fetch($sql, $pars);
        if(!empty($exists)) {
            return error(-1, '标题重复, 请更换');
        }

        $ret = pdo_insert($this->Table_activity, $rec);
        if(!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }

    public function modify($id,$entity){
        global $_W;
        $id = intval($id);
        if(count($entity) > 2){
            $rec = array_elements(array('title','description','shareimg','content','singleimg','status','extype','starttime','endtime','place','proname','tlimit','scantimes','fee','author','groups','viewnums'), $entity);
        }else{
            $rec = $entity;
        }
        $rec['uniacid'] = $_W['uniacid'];
        $condition = '`uniacid`=:uniacid AND `title`=:title AND `id`!=:id';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':title'] = $rec['title'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->Table_activity) . " WHERE {$condition}";
        $exists = pdo_fetch($sql, $pars);
        if(!empty($exists)) {
            return error(-1, '标题重复, 请更换');
        }
        $ret = pdo_update($this->Table_activity, $rec, array('id'=>$id, 'uniacid'=>$rec['uniacid']));
        if($ret){
            return true;
        }else{
            return false;
        }

    }

    public function getAll($filters = array(), $pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        $pars = array();
        $condition = "`uniacid`=:uniacid ";
        if(!empty($filters['status'])){
            $condition.=" AND `status`=:status";
            $pars[':status'] = $filters['status'];
        }
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = "SELECT * FROM ".tablename($this->Table_activity)." WHERE {$condition} ORDER BY `id` DESC";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_activity) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_activity) . " WHERE {$condition} ORDER BY `id` DESC  LIMIT {$start},{$psize}";
        }
        $ds = pdo_fetchall($sql,$pars);
        if(!empty($ds)) {
            return $ds;
        }
        return false;
    }

    public function getOne($id){
        global $_W;
        $condition = '`uniacid`=:uniacid AND `id`=:id';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->Table_activity) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            $sql = "SELECT * FROM " . tablename($this->Table_order) . " WHERE `actid`=" . intval($entity['id'])." AND `status`!=1 ";
            $entity['order'] = pdo_fetchall($sql);
            $entity['used'] = count($entity['order']);
            return $entity;
        }else{
            return false;
        }

    }

    public function remove($actid){
        pdo_query('DELETE FROM ' . tablename($this->Table_activity) . " WHERE `id`= {$actid} ");
        pdo_query('DELETE FROM ' . tablename($this->Table_order) . " WHERE `actid`= {$actid} ");
        pdo_query('DELETE FROM ' . tablename($this->Table_log) . " WHERE `actid`= {$actid} ");
        return true;

    }

    public function calcCount($actid){
        global $_W;
        $condition = '`actid`=:actid AND `uniacid`=:uniacid';
        $pars = array();
        $pars[':actid'] = $actid;
        $pars[':uniacid']  = $_W['uniacid'];
        $ret = array();
        $sql = 'SELECT COUNT(*) FROM ' . tablename($this->Table_order) . " WHERE {$condition} AND `status`!=1";
        $ret['total'] = pdo_fetchcolumn($sql, $pars);
        return $ret;
    }
}