<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbsk_records`;");
E_C("CREATE TABLE `ims_mbsk_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `log` varchar(500) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL,
  `device` int(10) unsigned NOT NULL DEFAULT '0',
  `distance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `created` int(10) unsigned NOT NULL,
  `completed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`),
  KEY `log` (`log`(333)),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `device` (`device`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>