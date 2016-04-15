<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_red_rule`;");
E_C("CREATE TABLE `ims_enjoy_red_rule` (
  `id` int(200) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) unsigned DEFAULT NULL,
  `rmax` int(20) DEFAULT NULL,
  `rmin` int(20) DEFAULT NULL,
  `rchance` int(10) DEFAULT NULL,
  `rcount` int(100) DEFAULT NULL,
  `createtime` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>