<?php

/**
 * Created by PhpStorm.
 * User: leon
 * Date: 15/9/5
 * Time: 下午2:04
 */
require_once dirname(__FILE__) . '/../../../lonaking_flash/FlashCommonService.php';
class TcGiftOrderService extends FlashCommonService
{
    public function __construct()
    {
        $this->plugin_name = 'lonaking_taskcenter';
        $this->table_name = 'lonaking_supertask_gift_order';
        $this->columns = 'id,uniacid,openid,order_num,gift,status,name,mobile,target,createtime,updatetime,pay_method,pay_status,trans_num,send_price';

    }

    /**
     * 获取用户某个礼品的兑换记录
     * @param $gift_id
     * @return mixed
     */
    public function getCustomGiftOrder($gift_id){
        global $_W;
        $openid = $_W['openid'];
        $orders = $this->selectAll("AND gift={$gift_id} AND openid='{$openid}'");
        return $orders;
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
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.order_num,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.raffle_status,o.order_mode,o.order_hongbao_money,g.name gift_name,g.price,g.pic,g.description,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address,g.raffle,g.hongbao_min,g.hongbao_max,g.hongbao_send_num FROM ". tablename('lonaking_supertask_gift') ." g LEFT JOIN ". tablename($this->table_name) ." o ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND o.openid='{$openid}' ORDER BY o.createtime DESC");
        return $gift_orders;
    }

    public function selectAllGiftOrders($where=''){
        global $_W;
        $uniacid = $_W['uniacid'];
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.order_num,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.raffle_status,o.order_mode,o.order_hongbao_money,g.name gift_name,g.price,g.pic,g.description,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address,g.raffle,g.hongbao_min,g.hongbao_max,g.hongbao_send_num FROM ". tablename('lonaking_supertask_gift') ." g LEFT JOIN ". tablename($this->table_name) ." o ON o.gift=g.id WHERE o.uniacid='{$uniacid}' AND 1=1 {$where} ORDER BY o.createtime DESC");
        return $gift_orders;
    }
    /**
     * 查询一条礼品订单信息
     * @param $id
     * @return bool
     */
    public function selectGiftOrdersDetail($id){
        $gift_order = pdo_fetch("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.order_num,o.gift gift ,o.status status,o.name real_name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.pay_status,o.pay_method,o.trans_num,o.order_num,o.send_price,o.order_price,o.raffle_status,o.order_mode,o.order_hongbao_money,g.name gift_name,g.price,g.pic,g.description,g.mode,g.send_price,g.mobile_fee_money,g.hongbao_money,g.ziling_address,g.ziling_mobile,g.raffle,g.hongbao_min,g.hongbao_max,g.hongbao_send_num FROM ". tablename('lonaking_supertask_gift') ." g LEFT JOIN ". tablename($this->table_name) ." o ON o.gift=g.id WHERE o.id='{$id}'");
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

    /**
     * 获取一个微信用户兑换的历史记录
     * @param $openid
     * @return array
     */
    public function getHistoryGiftOrders($openid){
        $gift_orders = pdo_fetchall("SELECT o.id id,o.uniacid uniacid,o.openid openid,o.gift gift,o.status status,o.name name,o.mobile mobile, o.target target,o.createtime createtime, o.updatetime updatetime,o.order_num,o.send_price,o.order_price,o.raffle_status,o.order_mode,o.order_hongbao_money,g.name as gift_name,g.description,g.raffle,g.hongbao_min,g.hongbao_max,g.hongbao_send_num FROM ". tablename($this->table_name) ." o JOIN ". tablename('lonaking_supertask_gift') ." g on o.gift=g.id WHERE o.openid=:openid ORDER BY o.createtime DESC",array(':openid'=>$openid));
        return $gift_orders;
    }

    /**
     * 获取礼品订单的状态
     * @param $giftOrder
     * @return string
     */
    public function getGiftOrderStatusText($giftOrder){
        if($giftOrder['order_mode'] == 1){//抽奖
            //抽奖
            if($giftOrder['raffle_status'] == 0){
                $status_text = '未中奖';
                return $status_text;
            }elseif($giftOrder['raffle_status'] == 1){
                return '已中奖('.$this->getOrdinaryGiftOrderStatusText($giftOrder).')';
            }
        }else{//不是抽奖
            return $this->getOrdinaryGiftOrderStatusText($giftOrder);
        }
    }

    /**
     * 获取普通非抽奖订单的状态
     * @param $giftOrder
     * @return string
     */
    private function getOrdinaryGiftOrderStatusText($giftOrder){
        $mode = $giftOrder['mode'];
        $status = $giftOrder['status'];
        $status_text = '待审核';
        //如果status=2的话直接就是未通过
        if($status == 2){
            return '未通过(积分已退回)';
        }
        //如果status＝0的话直接就是待审核
        if($status == 0){
            return '待审核';
        }
        if($mode == 1){
            //微信红包
            if($status == 1){
                $status_text = '红包已发放';
            }
        }elseif($mode == 2){
            //充值
            if($status == 1){
                $status_text = '已充值';
            }
        }elseif($mode == 3){
            //实物礼品
            if($status == 1){
                if($giftOrder['trans_num'] == 0){
                    $status_text = '待发货';
                }else{
                    $status_text = '已发货';
                }
            }
        }elseif($mode == 4){
            //自领礼品
            if($status == 1){
                $status_text = '已审核';
            }elseif($status == 5){
                $status_text = '已领取';
            }
        }
        return $status_text;
    }
}
