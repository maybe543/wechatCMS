<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_position_range`;");
E_C("CREATE TABLE `ims_enjoy_recuit_position_range` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `pid` int(10) NOT NULL,
  `maxage` int(10) DEFAULT NULL,
  `minage` int(10) DEFAULT NULL,
  `maxsalary` int(10) DEFAULT NULL,
  `minsalary` int(10) DEFAULT NULL,
  `maxexper` int(10) DEFAULT NULL,
  `minexper` int(10) DEFAULT NULL,
  `param_1` varchar(20) DEFAULT NULL,
  `param_2` varchar(20) DEFAULT NULL,
  `param_3` varchar(20) DEFAULT NULL,
  `param_4` varchar(20) DEFAULT NULL,
  `param_5` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>