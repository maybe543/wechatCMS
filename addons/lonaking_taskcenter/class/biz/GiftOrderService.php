<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/5
 * Time: 下午2:04
 */
require_once 'CommonService.php';
class GiftOrderService extends CommonService
{


    /**
     * GiftOrderService constructor.
     */
    public function __construct()
    {
        $this->table_name = 'lonaking_supertask_gift_order';
        $this->columns = 'id,uniacid,openid,order_num,gift,status,name,mobile,target,createtime,updatetime,pay_method,pay_status,trans_num,send_price';

    }

    /**
     * 查询当前用户所有的的礼品兑换记录，会将礼品信息查询出来
     * @param null $openid
     * @return array
     */
    public function selectMyOrdersWithGiftInfo($openid = null){
        global $_W;
        $uniacid = $_W['uniacid'];
        $openid = empty($openid) ? $_W['openid'] : $openid;
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.uid uid,o.openid openid,o.order_num,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,g.name gift_name,g.price,g.pic,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address FROM ". tablename('lonaking_supertask_gift') ." g LEFT JOIN ". tablename($this->table_name) ." o ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND o.openid='{$openid}' ORDER BY o.createtime DESC");
        return $gift_orders;
    }

    /**
     * 查询一条礼品订单信息
     * @param $id
     * @return bool
     */
    public function selectGiftOrdersDetail($id){
        $gift_order = pdo_fetch("SELECT o.id id,o.uniacid uniacid,o.uid uid,o.openid openid,o.order_num,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,g.name gift_name,g.price,g.pic,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address,g.ziling_mobile FROM ". tablename('lonaking_supertask_gift') ." g LEFT JOIN ". tablename($this->table_name) ." o ON o.gift=g.id WHERE o.id='{$id}'");
        return $gift_order;
    }

    /**
     * 查询giftOrder
     * @param $order_num
     * @return bool
     */
    public function selectByOrderNum($order_num){
        $gift_order = pdo_fetch("SELECT ".$this->columns." FROM ".tablename($this->table_name)." WHERE order_num=:order_num",array(':order_num'=>$order_num));
        return $gift_order;
    }
}