<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `ims_ks_key_game`;");
E_C("CREATE TABLE `ims_ks_key_game` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(9999) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>