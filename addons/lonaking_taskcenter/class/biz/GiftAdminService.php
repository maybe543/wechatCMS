<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/27
 * Time: 下午9:54
 */
require_once 'CommonService.php';
class GiftAdminService extends CommonService
{
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_gift_admin';
        $this->columns = 'id,uniacid,openid,gift_id';
    }

    /**
     * 根据openid查找管理员
     * @param $openid
     * @return bool
     */
    public function selectByOpenid($openid){
        global $_W;
        $uniacid = $_W['uniacid'];
        $admins = pdo_fetchall("SELECT ". $this->columns ." FROM ". tablename($this->table_name) ." WHERE openid=:openid AND uniacid=:uniacid",array(':openid'=>$openid,':uniacid'=>$uniacid));
        return $admins;
    }

    public function selectByOpenidAndGiftId($openid,$gift_id){
        global $_W;
        $uniacid = $_W['uniacid'];
        $admin = pdo_fetch("SELECT ". $this->columns ." FROM ". tablename($this->table_name) ." WHERE openid=:openid AND uniacid=:uniacid AND gift_id=:gift_id",array(':openid'=>$openid,':uniacid'=>$uniacid,':gift_id'=>$gift_id));
        return $admin;
    }
}