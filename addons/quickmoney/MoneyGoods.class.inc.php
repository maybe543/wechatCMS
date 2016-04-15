<?php
class MoneyGoods{
    public function getExchangeTypeStr($type){
        switch ($type){
        case 1: $str = '支付宝';
            break;
        case 2: $str = '银行卡';
            break;
        case 3: $str = '微信支付';
            break;
        default: $str = '未定义';
            break;
        }
        return $str;
    }
}
?>
