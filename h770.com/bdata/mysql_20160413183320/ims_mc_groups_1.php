<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mc_groups`;");
E_C("CREATE TABLE `ims_mc_groups` (
  `groupid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL DEFAULT '',
  `orderlist` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `isdefault` tinyint(4) NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`groupid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ims_mc_groups` values('1','1',0xe9bb98e8aea4e4bc9ae59198e7bb84,'0','1','0');");
E_D("replace into `ims_mc_groups` values('2','3',0xe9bb98e8aea4e4bc9ae59198e7bb84,'0','1','0');");

require("../../inc/footer.php");
?>