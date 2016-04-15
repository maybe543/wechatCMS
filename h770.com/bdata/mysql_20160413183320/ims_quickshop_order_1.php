<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickshop_order`;");
E_C("CREATE TABLE `ims_quickshop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL COMMENT '包括商品总价格,快递价格,并扣除了折扣，最终支付以此为准',
  `discount` decimal(10,2) DEFAULT '0.00' COMMENT '折扣',
  `dispatchprice` decimal(10,2) DEFAULT '0.00' COMMENT '快递费',
  `goodsprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品总价',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1取消状态，2普通状态，3为已付款，4为已发货，5为成功，6确认交易无纠纷',
  `sendtype` tinyint(2) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(2) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `usecredit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为不使用人人豆,1为使用人人豆',
  `creditused` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人人豆实际用量',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '' COMMENT '快递中文名',
  `express` varchar(200) NOT NULL DEFAULT '' COMMENT '快递英文代号',
  `expresssn` varchar(50) NOT NULL DEFAULT '' COMMENT '快递单号',
  `dispatch` int(10) DEFAULT '0' COMMENT '物流手段,自提,物流,快递等不同价位的邮递服务',
  `createtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_order_from_user` (`weid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>