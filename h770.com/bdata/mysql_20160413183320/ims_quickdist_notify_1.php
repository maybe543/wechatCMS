<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickdist_notify`;");
E_C("CREATE TABLE `ims_quickdist_notify` (
  `weid` int(10) NOT NULL,
  `level` int(10) NOT NULL DEFAULT '3',
  `param` varchar(10240) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>