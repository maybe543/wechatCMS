<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_circle_topic`;");
E_C("CREATE TABLE `ims_enjoy_circle_topic` (
  `tid` int(255) NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `hot` int(100) DEFAULT NULL,
  `zan` varchar(500) DEFAULT NULL,
  `cuid` int(200) DEFAULT '0',
  `joinnum` int(200) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>