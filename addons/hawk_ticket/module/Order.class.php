<?php
class Order{
    private $Table_order = 'hticket_order';

    public  function create($entity){
        global $_W;
        $input = array_elements(array('actid','fee'), $entity);
        $input['uniacid'] = $_W['uniacid'];
        $input['createtime'] = TIMESTAMP;
        $input['openid'] = $_W['fans']['from_user'];
        $input['status'] =1;
        if(empty($input['openid'])){
            return false;
        }
        $ret = pdo_insert($this->Table_order, $input);
        if(!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }

    public function modify($id,$entity){
        global $_W;
        $id = intval($id);
        $parms= array();
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE `id`=:id ";
        $parms[':id'] = $id;
        $exits = pdo_fetch($sql,$parms);
        if($exits){
            $update = $entity;
            $ret = pdo_update($this->Table_order, $update, array('id'=>$id));
            if($ret){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    public function getOrders($id){
        $id = intval($id);
        $parms = array();
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE `actid`=:actid  AND `status` !=1 ";
        $parms[':actid'] = $id;
        $res = pdo_fetchall($sql,$parms);
        if($res){
            return $res;
        }
        return false;
    }
    public function getMyOrders($filters=array(),$pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        load()->model('mc');
        $condition = " `uniacid`=:uniacid AND `openid`=:openid ";
        $parms = array();
        $parms[':uniacid'] = $_W['uniacid'];
        $openid = $_W['fans']['from_user'];
        $parms[':openid'] = $openid;
        if(!empty($filters['status'])){
            $condition.= " AND `status`=:status ";
            $parms[':status'] = $filters['status'];
        }
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE {$condition} ORDER BY `id` DESC  ";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_order) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $parms);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_order) . " WHERE {$condition} ORDER BY `id` DESC  LIMIT {$start},{$psize}";
        }
        $res = pdo_fetchall($sql,$parms);
        if($res){

            if(is_array($res)){
                foreach($res as $k=>&$v){
                    //增加核销人
                    if(!empty($v['scanown'])){
                        $fan = mc_fansinfo($v['scanown']);
                        $v['scanname'] = $fan['nickname'];
                    }
                }
            }
            return $res;
        }
        return false;
    }

    public function getMyMaster($filters=array(),$pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        require_once(MODULE_ROOT.'/module/Activity.class.php');
        $act = new Activity();
        load()->model('mc');
        $condition = " `uniacid`=:uniacid AND `scanown`=:scanown ";
        $parms = array();
        $parms[':uniacid'] = $_W['uniacid'];
        $openid = $_W['fans']['from_user'];
        $parms[':scanown'] = $openid;
        if(!empty($filters['status'])){
            $condition.= " AND `status`=:status ";
            $parms[':status'] = $filters['status'];
        }
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE {$condition} ORDER BY `id` DESC  ";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_order) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $parms);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_order) . " WHERE {$condition} ORDER BY `id` DESC  LIMIT {$start},{$psize}";
        }
        $res = pdo_fetchall($sql,$parms);
        if($res){

            if(is_array($res)){
                foreach($res as $k=>&$v){
                    //增加核销人信息
                    if(!empty($v['scanown'])){
                        $fan = mc_fansinfo($v['scanown']);
                        $v['scanname'] = $fan['nickname'];
                    }
                    //增加活动信息
                    $actinfo = $act->getOne($v['actid']);
                    $v['actinfo'] = $actinfo;
                    //增加票据人信息
                    $uid = mc_openid2uid($v['openid']);
                    $meminfo = mc_fetch($uid,array('nickname','avatar','mobile'));
                    $v['meminfo'] = $meminfo;
                }
            }
            return $res;
        }
        return false;
    }

    public function getAll($filters=array(),$pindex = 0, $psize = 20, &$total = 0){
        global $_W;
        load()->model('mc');
        $condition = " `uniacid`=:uniacid ";
        $parms = array();
        $parms[':uniacid'] = $_W['uniacid'];
        if(!empty($filters['actid'])){
            $condition.= " AND `actid`=:actid ";
            $parms[':actid'] = $filters['actid'];
        }
        if(!empty($filters['status'])){
            $condition.= " AND `status`=:status ";
            $parms[':status'] = $filters['status'];
        }else{
            $condition.= " AND `status`!=1 ";
        }
        if(!empty($filters['scanown'])){
            $condition.= " AND `scanown`=:scanown ";
            $parms[':scanown'] = $filters['scanown'];
        }
        if(!empty($filters['openid'])){
            $condition.= " AND `openid`=:openid ";
            $parms[':openid'] = $filters['openid'];
        }
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE {$condition} ORDER BY `id` DESC  ";
        if($pindex > 0){
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_order) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $parms);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_order) . " WHERE {$condition} ORDER BY `id` DESC  LIMIT {$start},{$psize}";
        }
        $res = pdo_fetchall($sql,$parms);
        if($res){

            if(is_array($res)){
                foreach($res as $k=>&$v){
                    //增加核销人
                    if(!empty($v['scanown'])){
                        $uid = mc_openid2uid($v['scanown']);
                        $v['scaninfo'] = mc_fetch($uid,array('nickname','avatar','mobile'));
                    }
                    //获取会员信息
                    if(!empty($v['openid'])){
                        $uid = mc_openid2uid($v['openid']);
                        $meminfo = mc_fetch($uid,array('nickname','avatar','mobile'));
                        $v['meminfo'] = $meminfo;
                    }
                }
            }
            return $res;
        }
        return false;
    }

    public function getOne($id,$status=2){
        global $_W;
        $id = intval($id);
        if(empty($id)){
            return false;
        }
        $params = array();
        $condition = " `uniacid`=:uniacid  AND `id`=:id ";
        if(!empty($status)){
            $condition.=" AND `status`=:status ";
            $params[':status'] = $status;
        }
        $params[':uniacid'] = $_W['uniacid'];
        $params[':id'] = $id;
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE {$condition} ";
        $res=pdo_fetch($sql,$params);
        if($res){
            return $res;
        }
        return false;
    }



    public function checkOrder($id,$openid,$status=''){
        global $_W;
        $id = intval($id);
        if(empty($id)){
            return false;
        }
        $condition = " `uniacid`=:uniacid AND `openid`=:openid AND `id`=:id ";
        $params = array();
        $params[':uniacid'] = $_W['uniacid'];
        $params[':openid'] = $openid;
        $params[':id'] = $id;
        if(!empty($status)){
            $condition .=" AND `status`=:status ";
            $params['status']=$status;
        }
        $sql = "SELECT * FROM ".tablename($this->Table_order)." WHERE {$condition} ";
        $res=pdo_fetch($sql,$params);
        if($res){
            return $res;
        }
        return false;
    }
}