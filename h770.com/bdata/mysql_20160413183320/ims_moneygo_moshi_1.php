<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_moneygo_moshi`;");
E_C("CREATE TABLE `ims_moneygo_moshi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shuzi` int(11) DEFAULT NULL,
  `sy` varchar(200) DEFAULT NULL,
  `bg` varchar(200) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>