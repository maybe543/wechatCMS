<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickdist_commission`;");
E_C("CREATE TABLE `ims_quickdist_commission` (
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `order_leader` varchar(50) NOT NULL,
  `order_openid` varchar(50) NOT NULL,
  `order_createtime` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '订单下包含本商品数量',
  `rate` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '代理佣金,取值为0.00到1.00',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '订单中本商品价格',
  `level` int(10) unsigned NOT NULL,
  `commission_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`weid`,`orderid`,`goodsid`,`order_leader`),
  KEY `indx_order_leader` (`weid`,`order_leader`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>