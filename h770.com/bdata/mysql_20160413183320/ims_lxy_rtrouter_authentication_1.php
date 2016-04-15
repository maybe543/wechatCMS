<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_rtrouter_authentication`;");
E_C("CREATE TABLE `ims_lxy_rtrouter_authentication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `routerid` int(11) DEFAULT NULL,
  `fromuser` varchar(100) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `result` int(11) DEFAULT NULL,
  `resultmemo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>