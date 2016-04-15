<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_activity`;");
E_C("CREATE TABLE `ims_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `ac_pic` varchar(100) NOT NULL,
  `begintime` int(11) unsigned DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `countlimit` int(5) NOT NULL,
  `countvirtual` int(5) DEFAULT '0',
  `visitsCount` int(11) DEFAULT '0',
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `acdes` varchar(500) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL,
  `location_p` varchar(100) NOT NULL COMMENT '所在地区_省',
  `location_c` varchar(100) NOT NULL COMMENT '所在地区_市',
  `location_a` varchar(100) NOT NULL COMMENT '所在地区_区',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `zb` varchar(50) DEFAULT NULL,
  `cb` varchar(50) DEFAULT NULL,
  `xb` varchar(50) DEFAULT NULL,
  `cjdx` varchar(50) DEFAULT NULL,
  `hoteldesc` varchar(500) DEFAULT NULL,
  `costdes` varchar(500) DEFAULT NULL,
  `isrepeat` int(1) DEFAULT '0',
  `istip` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>