<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbrp_trades`;");
E_C("CREATE TABLE `ims_mbrp_trades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `item` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `paid` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `activity` (`activity`),
  KEY `uid` (`uid`),
  KEY `item` (`item`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>