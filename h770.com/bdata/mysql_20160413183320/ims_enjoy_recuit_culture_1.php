<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_recuit_culture`;");
E_C("CREATE TABLE `ims_enjoy_recuit_culture` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `cname` varchar(200) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `place` varchar(200) DEFAULT NULL,
  `intro` longtext,
  `cact` longtext,
  `culture` longtext,
  `quest` longtext,
  `share_title` varchar(500) DEFAULT NULL,
  `share_desc` varchar(500) DEFAULT NULL,
  `share_icon` varchar(500) DEFAULT NULL,
  `share_credit` int(50) DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>