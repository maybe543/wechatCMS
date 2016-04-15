<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_egg_prize`;");
E_C("CREATE TABLE `ims_mon_egg_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sn` varchar(100) DEFAULT NULL,
  `egid` int(10) DEFAULT NULL,
  `plevel` varchar(50) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `pimg` varchar(500) DEFAULT NULL,
  `ptype` int(1) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `jf` int(10) DEFAULT '0',
  `pcount` int(10) DEFAULT NULL,
  `display_order` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>