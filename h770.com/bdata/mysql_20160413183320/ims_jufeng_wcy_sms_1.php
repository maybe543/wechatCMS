<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jufeng_wcy_sms`;");
E_C("CREATE TABLE `ims_jufeng_wcy_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `email` varchar(50) NOT NULL,
  `emailpsw` varchar(100) NOT NULL,
  `smtp` varchar(50) NOT NULL,
  `smsnum` varchar(50) NOT NULL,
  `smspsw` varchar(50) NOT NULL,
  `smstest` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>