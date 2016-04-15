<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_account`;");
E_C("CREATE TABLE `ims_account` (
  `acid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `hash` varchar(8) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `isconnect` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`acid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8");
E_D("replace into `ims_account` values('1','1',0x7552723871765156,'1','0');");
E_D("replace into `ims_account` values('2','2','','3','0');");
E_D("replace into `ims_account` values('3','3',0x587050527048746a,'1','0');");

require("../../inc/footer.php");
?>