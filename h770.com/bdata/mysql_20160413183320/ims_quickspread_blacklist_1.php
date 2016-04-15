<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickspread_blacklist`;");
E_C("CREATE TABLE `ims_quickspread_blacklist` (
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `weid` int(10) unsigned NOT NULL,
  `access_time` int(10) unsigned NOT NULL,
  `hit` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`from_user`,`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>