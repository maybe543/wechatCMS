<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_sign_record`;");
E_C("CREATE TABLE `ims_mon_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `sid` int(10) DEFAULT '0',
  `sin_time` int(10) DEFAULT '0',
  `credit` int(10) NOT NULL,
  `sign_type` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>