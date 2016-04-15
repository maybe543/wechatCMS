<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_yzmhb_code`;");
E_C("CREATE TABLE `ims_ice_yzmhb_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `code` varchar(64) NOT NULL DEFAULT '1',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `yzmhbid` int(4) unsigned NOT NULL DEFAULT '1',
  `piciid` int(4) unsigned NOT NULL DEFAULT '1',
  `type` char(1) DEFAULT '1',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>