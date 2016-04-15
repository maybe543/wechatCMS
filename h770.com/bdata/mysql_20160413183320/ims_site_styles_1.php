<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_site_styles`;");
E_C("CREATE TABLE `ims_site_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `templateid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ims_site_styles` values('1','1','1',0xe5beaee7ab99e9bb98e8aea4e6a8a1e69dbf5f67433543);");
E_D("replace into `ims_site_styles` values('2','3','1',0xe5beaee7ab99e9bb98e8aea4e6a8a1e69dbf5f696e6b5a);");

require("../../inc/footer.php");
?>