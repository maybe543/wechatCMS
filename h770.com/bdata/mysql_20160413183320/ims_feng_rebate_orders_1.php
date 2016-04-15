<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_feng_rebate_orders`;");
E_C("CREATE TABLE `ims_feng_rebate_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordersn` varchar(45) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `transid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(45) NOT NULL,
  `pay_type` int(11) NOT NULL,
  `createtime` varchar(45) NOT NULL,
  `status` int(11) NOT NULL,
  `ptime` varchar(45) NOT NULL,
  `price` varchar(45) NOT NULL,
  `goodsn` varchar(45) NOT NULL COMMENT '商品上架标识',
  `get` int(11) NOT NULL COMMENT '0未中1已中奖',
  `recvname` varchar(45) NOT NULL COMMENT '收货人名',
  `mobile` varchar(45) NOT NULL,
  `address` varchar(45) NOT NULL,
  `zjm` varchar(45) NOT NULL COMMENT '中奖码',
  `express` varchar(45) NOT NULL COMMENT '快递公司',
  `expresssn` varchar(45) NOT NULL COMMENT '快递单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>