<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_basic`;");
E_C("CREATE TABLE `ims_enjoy_recuit_basic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `uname` varchar(20) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `ed` varchar(10) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `avatar` longtext,
  `present` varchar(200) DEFAULT NULL,
  `italy` int(2) DEFAULT '0',
  `createtime` varchar(30) DEFAULT NULL,
  `param_1` varchar(50) DEFAULT NULL,
  `param_2` varchar(50) DEFAULT NULL,
  `param_3` varchar(50) DEFAULT NULL,
  `param_4` varchar(50) DEFAULT NULL,
  `param_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>