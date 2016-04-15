<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickspread_top_cache`;");
E_C("CREATE TABLE `ims_quickspread_top_cache` (
  `weid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `cache` text NOT NULL,
  PRIMARY KEY (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>