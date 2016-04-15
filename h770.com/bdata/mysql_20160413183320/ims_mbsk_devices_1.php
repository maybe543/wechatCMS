<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbsk_devices`;");
E_C("CREATE TABLE `ims_mbsk_devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `activity` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `device_id` int(10) unsigned NOT NULL,
  `uuid` varchar(50) NOT NULL,
  `major` int(10) unsigned NOT NULL,
  `minor` int(10) unsigned NOT NULL,
  `audit_status` int(11) NOT NULL,
  `audit_comment` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0: 未激活, 1:已激不活跃, 2: 活跃',
  PRIMARY KEY (`id`),
  KEY `uuid` (`uuid`,`major`,`minor`),
  KEY `uniacid` (`uniacid`),
  KEY `device_id` (`device_id`),
  KEY `activity` (`activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>