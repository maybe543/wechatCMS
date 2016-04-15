<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_deliver`;");
E_C("CREATE TABLE `ims_enjoy_recuit_deliver` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `pid` int(10) NOT NULL,
  `createtime` int(30) DEFAULT NULL,
  `param_1` varchar(50) DEFAULT NULL,
  `param_2` varchar(50) DEFAULT NULL,
  `param_3` varchar(50) DEFAULT NULL,
  `param_4` varchar(50) DEFAULT NULL,
  `param_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>