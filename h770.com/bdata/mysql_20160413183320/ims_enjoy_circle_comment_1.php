<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_circle_comment`;");
E_C("CREATE TABLE `ims_enjoy_circle_comment` (
  `cid` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `tid` int(255) DEFAULT NULL,
  `comment` varchar(10000) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `cuid` int(200) DEFAULT '0',
  `hot` int(50) DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>