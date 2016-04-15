<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `ims_ks_key`;");
E_C("CREATE TABLE `ims_ks_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(9999) NOT NULL,
  `use` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>