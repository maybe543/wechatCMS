<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_rule`;");
E_C("CREATE TABLE `ims_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `module` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8");
E_D("replace into `ims_rule` values('1','0',0xe59f8ee5b882e5a4a9e6b094,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('2','0',0xe799bee5baa6e799bee7a791,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('3','0',0xe58db3e697b6e7bfbbe8af91,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('4','0',0xe4bb8ae697a5e88081e9bb84e58e86,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('5','0',0xe79c8be696b0e997bb,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('6','0',0xe5bfabe98092e69fa5e8afa2,0x75736572617069,'255','1');");
E_D("replace into `ims_rule` values('7','1',0xe4b8aae4babae4b8ade5bf83e585a5e58fa3e8aebee7bdae,0x636f766572,'0','1');");
E_D("replace into `ims_rule` values('8','1',0xe5beaee8b59ee59ba2e9989fe585a5e58fa3e8aebee7bdae,0x636f766572,'0','1');");

require("../../inc/footer.php");
?>