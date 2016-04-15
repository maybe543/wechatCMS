<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_sign_award`;");
E_C("CREATE TABLE `ims_mon_sign_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned DEFAULT NULL,
  `uid` int(10) NOT NULL,
  `sign_type` int(2) NOT NULL,
  `serial_start_time` int(10) DEFAULT NULL,
  `serial_end_time` int(10) DEFAULT NULL,
  `serial_day` int(10) DEFAULT NULL,
  `credit` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>