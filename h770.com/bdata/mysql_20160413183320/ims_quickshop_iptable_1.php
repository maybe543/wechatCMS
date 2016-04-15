<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickshop_iptable`;");
E_C("CREATE TABLE `ims_quickshop_iptable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `ip` varchar(64) NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `title` varchar(128) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `begger` varchar(50) NOT NULL DEFAULT '' COMMENT '请求杀价者',
  `giver` varchar(50) NOT NULL DEFAULT '' COMMENT '帮忙杀价着',
  `givername` varchar(50) NOT NULL DEFAULT '' COMMENT '帮忙杀价着',
  `exchangetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>