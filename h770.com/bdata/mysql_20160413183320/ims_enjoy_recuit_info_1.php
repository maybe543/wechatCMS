<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_info`;");
E_C("CREATE TABLE `ims_enjoy_recuit_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `openid` varchar(100) NOT NULL,
  `birth` varchar(50) DEFAULT NULL,
  `register` varchar(200) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `marriage` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `school` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `param_1` varchar(50) DEFAULT NULL,
  `param_2` varchar(50) DEFAULT NULL,
  `param_3` varchar(50) DEFAULT NULL,
  `param_4` varchar(50) DEFAULT NULL,
  `param_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>