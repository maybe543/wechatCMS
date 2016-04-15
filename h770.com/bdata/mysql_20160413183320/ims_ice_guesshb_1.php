<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_guesshb`;");
E_C("CREATE TABLE `ims_ice_guesshb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `codeid` int(10) DEFAULT '0',
  `uniacid` int(10) DEFAULT '0',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `gettime` varchar(64) DEFAULT '',
  `guess_count` int(10) DEFAULT '0',
  `money` int(10) DEFAULT '0',
  `interval` varchar(16) DEFAULT '',
  `hastime` varchar(64) NOT NULL DEFAULT '1',
  `status` char(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>