<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_view`;");
E_C("CREATE TABLE `ims_enjoy_recuit_view` (
  `id` int(100) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `pid` int(10) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>