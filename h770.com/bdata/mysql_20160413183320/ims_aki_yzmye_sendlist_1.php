<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_aki_yzmye_sendlist`;");
E_C("CREATE TABLE `ims_aki_yzmye_sendlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `piciid` int(10) DEFAULT '0',
  `codeid` int(10) DEFAULT '0',
  `openid` varchar(64) DEFAULT '',
  `yzmyeid` varchar(32) DEFAULT '',
  `yue` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT '',
  `time` varchar(20) DEFAULT '1',
  `mark` varchar(128) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>