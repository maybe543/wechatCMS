<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_cart_notes`;");
E_C("CREATE TABLE `ims_cate_cart_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0',
  `cartid` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `type` varchar(50) DEFAULT NULL,
  `content` text,
  `visible` tinyint(3) unsigned DEFAULT '0',
  `indate` bigint(18) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 订单操作记录'");

require("../../inc/footer.php");
?>