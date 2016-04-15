<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_feng_rebate_member`;");
E_C("CREATE TABLE `ims_feng_rebate_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(45) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `avatar` varchar(45) NOT NULL,
  `nickname` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>