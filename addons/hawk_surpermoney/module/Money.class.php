<?php

class Money {
    //columns id uniacid title description content money createtime author
    // question answer viewnums limit allow
    private $Table_article='hmoney_article';
    private $Table_records='hmoney_records';
    public function create($entity) {
        global $_W;
        $rec = array_elements(array('title','description','content','money','first','second','author','status','type','totalmoney','starttime','endtime','reurl','shareimg','viewnums','limit','allow'), $entity);
        $rec['uniacid'] = $_W['uniacid'];
        $rec['createtime'] = TIMESTAMP;
        $condition = '`uniacid`=:uniacid AND `title`=:title';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':title'] = $rec['title'];
        $sql = 'SELECT * FROM ' . tablename($this->Table_article) . " WHERE {$condition}";
        $exists = pdo_fetch($sql, $pars);
        if(!empty($exists)) {
            return error(-1, '标题重复, 请更换');
        }

        $ret = pdo_insert($this->Table_article, $rec);
        if(!empty($ret)) {
            $id = pdo_insertid();
            return $id;
        }
        return false;
    }

    public function modify($id, $entity) {
        global $_W;
        $id = intval($id);
        if(count($entity) > 2){
            $rec = array_elements(array('title','description','content','money','first','second','author','status','type','totalmoney','starttime','endtime','reurl','shareimg','viewnums','limit','allow'), $entity);
        }else{
            $rec = $entity;
        }
        $rec['uniacid'] = $_W['uniacid'];
        $condition = '`uniacid`=:uniacid AND `title`=:title AND `id`!=:id';
        $pars = array();
        $pars[':uniacid'] = $rec['uniacid'];
        $pars[':title'] = $rec['title'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->Table_article) . " WHERE {$condition}";
        $exists = pdo_fetch($sql, $pars);
        if(!empty($exists)) {
            return error(-1, '标题重复, 请更换');
        }
        $ret = pdo_update($this->Table_article, $rec, array('id'=>$id, 'uniacid'=>$rec['uniacid']));
        if($ret){
            return true;
        }else{
            return false;
        }
    }


    public function remove($id) {
        global $_W;
        $pars = array();
        $pars[':id'] = $id;
        $pars[':uniacid'] = $_W['uniacid'];
        pdo_query('DELETE FROM ' . tablename($this->Table_article) . " WHERE `id`=:id AND `uniacid`=:uniacid ", $pars);
        pdo_query('DELETE FROM ' . tablename($this->Table_records) . " WHERE `uniacid`=:uniacid AND `articleid`=:id", $pars);
        return true;
    }

    public function getOne($id) {
        global $_W;
        $condition = '`uniacid`=:uniacid AND `id`=:id';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':id'] = $id;
        $sql = 'SELECT * FROM ' . tablename($this->Table_article) . " WHERE {$condition}";
        $entity = pdo_fetch($sql, $pars);
        if(!empty($entity)) {
            $sql = "SELECT * FROM " . tablename($this->Table_records) . " WHERE `articleid`=" . intval($entity['articleid']);
            $entity['records'] = pdo_fetchall($sql);
            return $entity;
        }else{
            return false;
        }
    }

    public function getAll($filters, $pindex = 0, $psize = 20, &$total = 0) {
        global $_W;
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if(!empty($filters['title'])) {
            $condition .= ' AND `title` LIKE :title';
            $pars[':title'] = "%{$filters['title']}%";
        }
        if(!empty($filters['status'])){
            $condition .= ' AND `status`= :status';
            $pars[':status'] = $filters['status'];
        }
        $sql = "SELECT * FROM " . tablename($this->Table_article) . " WHERE {$condition} ORDER BY `id` DESC";
        if($pindex > 0) {
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_article) . " WHERE {$condition}";
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM " . tablename($this->Table_article) . " WHERE {$condition} ORDER BY `id` DESC LIMIT {$start},{$psize}";
        }
        $ds = pdo_fetchall($sql, $pars);
        if(!empty($ds)) {
            foreach($ds as &$row) {
                $sql = "SELECT * FROM " . tablename($this->Table_records) . " WHERE `articleid`=" . intval($row['id']);
                $row['records'] = pdo_fetchall($sql);
            }
        }
        return $ds;
    }

    public function getRecord($openid, $reid) {
        global $_W;
        $condition = "`uniacid`=:uniacid AND `openid`=:openid AND `id`=:id";
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        $pars[':openid'] = $openid;
        $pars[':id'] = $reid;
        $sql = 'SELECT * FROM ' . tablename($this->Table_records) . " WHERE {$condition}";
        $rec = pdo_fetch($sql, $pars);
        return $rec;
    }

    public function getRecords($filters, $pindex = 0, $psize = 20, &$total = 0) {
        global $_W;
        $fan = new Fan();
        $condition = '`uniacid`=:uniacid';
        $pars = array();
        $pars[':uniacid'] = $_W['uniacid'];
        if(!empty($filters['articleid'])) {
            $condition .= ' AND `articleid`=:articleid';
            $pars[':articleid'] = $filters['articleid'];
        }
        if(!empty($filters['openid'])) {
            $condition .= ' AND `openid`=:openid';
            $pars[':openid'] = $filters['openid'];
        }
        $sql = "SELECT * FROM " . tablename($this->Table_records)." WHERE {$condition} ORDER BY `id` DESC ";
        if($pindex > 0) {
            $sql = "SELECT COUNT(*) FROM " . tablename($this->Table_records)." WHERE {$condition}" ;
            $total = pdo_fetchcolumn($sql, $pars);
            $start = ($pindex - 1) * $psize;
            $sql = "SELECT * FROM" . tablename($this->Table_records)." WHERE {$condition} ORDER BY `id` DESC, `createtime` DESC LIMIT {$start},{$psize}" ;
        }
        $ds = pdo_fetchall($sql, $pars);
        if(!empty($ds)) {
            foreach($ds as &$row) {
                $fands = $fan->getOne($row['openid']);
                if(!empty($fands)){
                    $row['nickname'] = $fands['nickname'];
                    $row['avatar'] = $fands['avatar'];
                }
            }
        }
        return $ds;
    }


    public function confirm($id) {
        global $_W;
        $filters = array();
        $filters['uniacid'] = $_W['uniacid'];
        $filters['id'] = $id;

        $rec = array();
        $rec['status'] = 'complete';
        $rec['completetime'] = TIMESTAMP;

        $ret = pdo_update($this->Table_records, $rec, $filters);
        if(!empty($ret)) {
            return $ret;
        }
        return false;
    }

    public function calcCount($id) {
        global $_W;
        $condition = '`articleid`=:articleid AND `uniacid`=:uniacid';
        $pars = array();
        $pars[':articleid'] = $id;
        $pars[':uniacid']  = $_W['uniacid'];
        $ret = array();
        $sql = 'SELECT COUNT(*) FROM ' . tablename($this->Table_records) . " WHERE {$condition}";
        $ret['total'] = pdo_fetchcolumn($sql, $pars);
        return $ret;
    }

}