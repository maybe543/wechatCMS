<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_tel114`;");
E_C("CREATE TABLE `ims_amouse_tel114` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `outlink` varchar(100) DEFAULT '',
  `mobile` varchar(100) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `status` varchar(1) DEFAULT '0' COMMENT '审核0 不审核1',
  `place` varchar(200) NOT NULL DEFAULT '无锡新区',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='电话号码'");

require("../../inc/footer.php");
?>