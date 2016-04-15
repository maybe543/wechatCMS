<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickcredit_log`;");
E_C("CREATE TABLE `ims_quickcredit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `credit` decimal(10,2) DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `delta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>