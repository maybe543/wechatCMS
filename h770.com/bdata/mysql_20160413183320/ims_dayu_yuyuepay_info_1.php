<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_dayu_yuyuepay_info`;");
E_C("CREATE TABLE `ims_dayu_yuyuepay_info` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `member` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '预约状态',
  `xmid` int(11) NOT NULL,
  `price` varchar(10) NOT NULL DEFAULT '',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `paystatus` tinyint(4) NOT NULL COMMENT '付款状态',
  `paytype` tinyint(4) NOT NULL COMMENT '付款方式',
  `yuyuetime` int(10) NOT NULL DEFAULT '0' COMMENT '客服确认时间',
  `kfinfo` varchar(100) NOT NULL COMMENT '客服信息',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>