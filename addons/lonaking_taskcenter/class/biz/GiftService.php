<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/4
 * Time: 下午12:04
 */
require_once 'CommonService.php';
class GiftService extends CommonService
{


    /**
     * GiftService constructor.
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_gift';
        $this->columns = 'id,uniacid,name,price,type,num,status,pic,mode,send_price,del,createtime,updatetime,mobile_fee_money,hongbao_money,ziling_address,ziling_mobile,check_password,description';
    }

    /**
     * 根据核销密码查找礼品
     * @param $check_password
     * @return bool
     */
    public function selectByCheckPassword($check_password){
        global $_W;
        $admin = pdo_fetch("SELECT " . $this->columns . " FROM " . tablename($this->table_name) . " WHERE check_password=:check_password AND uniacid=:uniacid", array(':check_password'=>$check_password,':uniacid'=>$_W['uniacid']));
        return $admin;
    }
}