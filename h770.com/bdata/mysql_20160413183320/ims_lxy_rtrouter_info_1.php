<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_rtrouter_info`;");
E_C("CREATE TABLE `ims_lxy_rtrouter_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `iurl` varchar(255) DEFAULT NULL,
  `rname` varchar(100) DEFAULT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `appkey` varchar(100) DEFAULT NULL,
  `nodeid` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>