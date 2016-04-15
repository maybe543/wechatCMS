<?php
class Log{
    private $Table_log = 'hticket_log';

    public  function create($entity){
        global $_W;
        $input = array_elements(array('orderid','actid','scanown','type','remark'), $entity);
        $input['uniacid'] = $_W['uniacid'];
        $input['createtime'] = TIMESTAMP;
        if(empty($input['scanown'])){
            return false;
        }
        $ret = pdo_insert($this->Table_log, $input);
        if(!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }

    public function getAll($id,$type=1){
        $id = intval($id);
        $parms = array();
        $sql = "SELECT * FROM ".tablename($this->Table_log)." WHERE `orderid`=:orderid AND `type`=:type  ";
        $parms[':orderid'] = $id;
        $parms[':type'] = $type;
        $res = pdo_fetchall($sql,$parms);
        if($res){
            return $res;
        }
        return false;
    }
    public function getMylog($filters = array(), $pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        $act = new Activity();
        $order = new Order();
        load()->model('mc');
        $pars = array();
        $condition = "`uniacid`=:uniacid ";
        if(!empty($filters['orderid'])){
            $condition.=" AND `orderid`=:orderid";
            $pars[':orderid'] = $filters['orderid'];
        }
        if(!empty($filters['scanown'])){
            $condition.=" AND `scanown`=:scanown";
            $pars[':scanown'] = $filters['scanown'];
        }
        if(!empty($filters['type'])){
            $condition.=" AND `type`=:type";
            $pars[':type'] = $filters['type'];
        }
        $pars[':uniacid'] = $_W['uniacid'];
        $sql = "SELECT * FROM ".tablename($this->Table_log)." WHERE {$condition} ORDER BY `id` DESC";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_log) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_log) . " WHERE {$condition} ORDER BY `id` DESC  LIMIT {$start},{$psize}";
        }
        $ds = pdo_fetchall($sql,$pars);
        if(!empty($ds)) {
            if(is_array($ds)){
                foreach($ds as $k=>&$v){
                    $actinfo = $act->getOne($v['actid']);
                    $v['actinfo'] = $actinfo;
                    $orderinfo = $order->getOne($v['orderid'],'');
                    $uid = mc_openid2uid($orderinfo['openid']);
                    $meminfo = mc_fetch($uid,array('nickname','avatar','mobile'));
                    $v['meminfo'] = $meminfo;
                }
            }
            return $ds;
        }
        return false;
    }

    public function remove($id){
        pdo_query('DELETE FROM ' . tablename($this->Table_log) . " WHERE `orderid`= {$id}  AND `type`=1 ");
        return true;

    }

}