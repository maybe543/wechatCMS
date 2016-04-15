<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_aki_yzmye_code`;");
E_C("CREATE TABLE `ims_aki_yzmye_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(64) NOT NULL DEFAULT '',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `yzmyeid` int(4) unsigned NOT NULL DEFAULT '0',
  `yue` decimal(10,2) DEFAULT '0.00',
  `piciid` int(4) unsigned NOT NULL DEFAULT '0',
  `type` char(1) DEFAULT '',
  `time` varchar(16) NOT NULL DEFAULT '',
  `status` char(1) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>