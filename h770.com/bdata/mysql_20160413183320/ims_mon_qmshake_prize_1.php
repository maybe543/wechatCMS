<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_qmshake_prize`;");
E_C("CREATE TABLE `ims_mon_qmshake_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `p_summary` varchar(500) DEFAULT NULL,
  `pimg` varchar(250) DEFAULT NULL,
  `p_url` varchar(250) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `pcount` int(10) DEFAULT NULL,
  `left_count` int(10) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `display_order` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `tgs` varchar(250) DEFAULT NULL,
  `tgs_url` varchar(1000) DEFAULT NULL,
  `virtual_count` int(10) DEFAULT NULL,
  `ptype` int(1) DEFAULT NULL,
  `jfye` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>