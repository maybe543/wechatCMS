<?php
 class Address{
    private static $t_address = 'quickshop_address';
    public function create($data){
        $id = -1;
        $ret = pdo_insert(self :: $t_address, $data);
        if (false !== $ret){
            $id = pdo_insertid();
        }
        return $id;
    }
    public function get($id){
        $address = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_address) . ' WHERE id=:id LIMIT 1', array(':id' => $id));
        return $address;
    }
    public function clientGet($weid, $openid, $id){
        $address = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_address) . ' WHERE id=:id AND weid=:weid AND openid=:openid LIMIT 1', array(':id' => $id, ':weid' => $weid, ':openid' => $openid));
        return $address;
    }
    public function find($data){
        $address = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_address) . ' WHERE weid=:weid AND openid=:openid ' . ' AND realname=:realname AND mobile=:mobile AND province=:province AND ' . ' city=:city AND area=:area AND address=:address LIMIT 1', array(':weid' => $data['weid'], ':openid' => $data['openid'], ':realname' => $data['realname'], ':mobile' => $data['mobile'], ':province' => $data['province'], ':city' => $data['city'], ':area' => $data['area'], ':address' => $data['address']));
        return $address;
    }
    public function getDefault($weid, $openid){
        $address = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_address) . ' WHERE openid=:openid AND isdefault = 1 LIMIT 1', array(':openid' => $openid));
        if (empty($address)){
            $address = pdo_fetch('SELECT * FROM ' . tablename(self :: $t_address) . ' WHERE openid=:openid ORDER BY id DESC LIMIT 1', array(':openid' => $openid));
        }
        if (empty($address)){
            yload() -> classs('quickcenter', 'wechatsetting');
            $_setting = new WechatSetting();
            $setting = $_setting -> get($weid, 'quickshop');
            $address = array();
            $address['province'] = $setting['default_province'];
            $address['city'] = $setting['default_city'];
            $address['area'] = $setting['default_area'];
            unset($setting);
        }
        return $address;
    }
    public function addDefault($weid, $openid, $data){
        pdo_update(self :: $t_address, array('isdefault' => 0), array('weid' => $weid, 'openid' => $openid));
        $data['isdefault'] = 1;
        return $this -> create($data);
    }
    public function changeDefault($weid, $openid, $id){
        pdo_update(self :: $t_address, array('isdefault' => 0), array('weid' => $weid, 'openid' => $openid));
        pdo_update(self :: $t_address, array('isdefault' => 1), array('weid' => $weid, 'id' => $id));
    }
    public function setDefault($weid, $openid, $id){
        pdo_update(self :: $t_address, array('isdefault' => 1), array('weid' => $weid, 'openid' => $openid, 'id' => $id));
    }
    public function update($weid, $id, $data){
        $ret = pdo_update(self :: $t_address, $data, array('weid' => $weid, 'id' => $id));
        return $ret;
    }
    public function markDelete($weid, $openid, $id, $isdefault = false){
        $maxid = $id;
        pdo_update(self :: $t_address, array("deleted" => 1, "isdefault" => 0), array('id' => $id, 'weid' => $weid));
        if ($isdefault){
            $maxid = pdo_fetchcolumn("SELECT max(id) as maxid FROM " . tablename(self :: $t_address) . " WHERE weid=:weid and openid=:openid LIMIT 1");
            if (!empty($maxid)){
                $this -> setDefault($weid, $openid, $maxid);
            }
        }
        return $maxid;
    }
    public function batchGet($weid, $conds){
        $condition = '';
        if (isset($conds['openid'])){
            $condition .= " AND openid = '" . intval($conds['openid']) . "'";
        }
        if (isset($conds['isdefault'])){
            $condition .= " AND isdefault = " . intval($conds['isdefault']) ;
        }
        $list = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_address) . " WHERE weid = $weid and deleted=0 $condition ORDER BY isdefault DESC, id DESC ");
        return $list;
    }
    public function batchGetByIds($weid, $ids, $key = null){
        $address = array();
        if (!empty($ids) and count($ids) > 0 and is_array($ids)){
            $address = pdo_fetchall("SELECT * FROM " . tablename(self :: $t_address) . " WHERE id IN ('" . implode("','", $ids) . "')", array(), $key);
        }
        return $address;
    }
}
