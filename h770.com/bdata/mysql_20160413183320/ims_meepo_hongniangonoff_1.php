<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_hongniangonoff`;");
E_C("CREATE TABLE `ims_meepo_hongniangonoff` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` int(2) NOT NULL DEFAULT '1',
  `weid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>