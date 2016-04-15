<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbrp_helps`;");
E_C("CREATE TABLE `ims_mbrp_helps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `owner` int(10) unsigned NOT NULL,
  `helper` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `uniacid` (`uniacid`),
  KEY `owner` (`owner`),
  KEY `helper` (`helper`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>