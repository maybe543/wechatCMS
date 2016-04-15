<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_sign_user`;");
E_C("CREATE TABLE `ims_mon_sign_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `begin_sign_time` int(10) DEFAULT NULL,
  `end_sign_time` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `headimgurl` varchar(200) DEFAULT NULL,
  `serial_id` int(10) DEFAULT NULL,
  `credit` int(10) DEFAULT '0',
  `sin_count` int(10) DEFAULT '0',
  `sin_serial` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>