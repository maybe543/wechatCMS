<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `ims_ks_yhq`;");
E_C("CREATE TABLE `ims_ks_yhq` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(999) NOT NULL,
  `desc` varchar(9999) NOT NULL,
  `code` varchar(500) NOT NULL,
  `images` varchar(999) NOT NULL,
  `link` varchar(999) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `code` (`code`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>