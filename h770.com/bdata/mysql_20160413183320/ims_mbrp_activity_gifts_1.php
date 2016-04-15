<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbrp_activity_gifts`;");
E_C("CREATE TABLE `ims_mbrp_activity_gifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activity` int(10) unsigned NOT NULL,
  `gift` int(10) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  `rate` decimal(6,2) NOT NULL COMMENT '中奖百分比率',
  PRIMARY KEY (`id`),
  KEY `activity` (`activity`),
  KEY `gift` (`gift`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>