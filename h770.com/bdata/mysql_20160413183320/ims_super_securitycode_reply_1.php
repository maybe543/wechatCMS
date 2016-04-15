<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_super_securitycode_reply`;");
E_C("CREATE TABLE `ims_super_securitycode_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `Reply` varchar(1000) NOT NULL,
  `Integral` int(10) NOT NULL,
  `tnumber` int(10) NOT NULL,
  `Failure` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>