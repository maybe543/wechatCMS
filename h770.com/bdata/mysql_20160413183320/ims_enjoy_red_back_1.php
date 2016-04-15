<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_red_back`;");
E_C("CREATE TABLE `ims_enjoy_red_back` (
  `id` int(200) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `money` float(50,2) NOT NULL DEFAULT '0.00',
  `createtime` int(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>