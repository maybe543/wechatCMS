<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stat_fans`;");
E_C("CREATE TABLE `ims_stat_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `new` int(10) unsigned NOT NULL,
  `cancel` int(10) unsigned NOT NULL,
  `cumulate` int(10) NOT NULL,
  `date` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`date`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8");
E_D("replace into `ims_stat_fans` values('1','3','0','0','0',0x3230313630333038);");
E_D("replace into `ims_stat_fans` values('2','3','0','0','0',0x3230313630333039);");
E_D("replace into `ims_stat_fans` values('3','3','0','0','0',0x3230313630333130);");
E_D("replace into `ims_stat_fans` values('4','3','0','0','0',0x3230313630333131);");
E_D("replace into `ims_stat_fans` values('5','3','0','0','0',0x3230313630333133);");
E_D("replace into `ims_stat_fans` values('6','3','0','0','0',0x3230313630333134);");
E_D("replace into `ims_stat_fans` values('7','3','0','0','0',0x3230313630333135);");
E_D("replace into `ims_stat_fans` values('8','3','0','0','0',0x3230313630333136);");
E_D("replace into `ims_stat_fans` values('9','3','0','0','0',0x3230313630333137);");
E_D("replace into `ims_stat_fans` values('10','9','0','0','0',0x3230313630333138);");
E_D("replace into `ims_stat_fans` values('11','3','0','0','0',0x3230313630333138);");

require("../../inc/footer.php");
?>