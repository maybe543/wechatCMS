<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_position`;");
E_C("CREATE TABLE `ims_enjoy_recuit_position` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `hot` int(10) DEFAULT '0',
  `sex` varchar(5) DEFAULT NULL,
  `ed` varchar(10) DEFAULT NULL,
  `height` int(5) DEFAULT NULL,
  `weight` int(5) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `key` varchar(50) DEFAULT NULL,
  `num` int(10) DEFAULT NULL,
  `place` varchar(50) DEFAULT NULL,
  `way` varchar(10) DEFAULT NULL,
  `descript` varchar(5000) DEFAULT NULL,
  `competence` varchar(5000) DEFAULT NULL,
  `views` varchar(10) DEFAULT '0',
  `deliveries` varchar(10) DEFAULT '0',
  `stime` varchar(50) DEFAULT NULL,
  `etime` varchar(50) DEFAULT NULL,
  `play` int(2) DEFAULT '0' COMMENT '暂停开始键',
  `param_2` varchar(50) DEFAULT NULL,
  `param_3` varchar(50) DEFAULT NULL,
  `param_4` varchar(50) DEFAULT NULL,
  `param_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>