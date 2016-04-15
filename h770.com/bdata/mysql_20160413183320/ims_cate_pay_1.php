<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_pay`;");
E_C("CREATE TABLE `ims_cate_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prepay_id` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT '0',
  `payid` int(10) unsigned DEFAULT '0',
  `type` varchar(20) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT '0',
  `cartids` text COMMENT '订单组',
  `cartmd5` varchar(40) DEFAULT NULL,
  `cartmd6` varchar(40) DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL COMMENT '商品描述',
  `num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支付总金额',
  `indate` bigint(18) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微餐饮 - 支付订单'");

require("../../inc/footer.php");
?>