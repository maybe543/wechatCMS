<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_shake_reply`;");
E_C("CREATE TABLE `ims_shake_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `cover` varchar(255) NOT NULL DEFAULT '',
  `qrcode` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `rule` text NOT NULL,
  `speed` int(10) unsigned NOT NULL DEFAULT '3000',
  `speedandroid` int(10) unsigned NOT NULL DEFAULT '8000',
  `interval` int(10) unsigned NOT NULL DEFAULT '100',
  `countdown` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `maxshake` int(10) unsigned NOT NULL DEFAULT '100',
  `maxwinner` int(10) unsigned NOT NULL DEFAULT '1',
  `maxjoin` int(10) unsigned NOT NULL,
  `joinprobability` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未开始，1为进行中，2为已结束',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>