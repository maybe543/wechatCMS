<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickspread_iptable`;");
E_C("CREATE TABLE `ims_quickspread_iptable` (
  `weid` int(10) unsigned NOT NULL,
  `ip` varchar(64) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `track_id` varchar(50) NOT NULL DEFAULT '',
  `track_type` varchar(20) NOT NULL DEFAULT '',
  `from_user` int(10) unsigned NOT NULL,
  `spreadid` int(10) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `access_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ip`,`weid`,`spreadid`,`access_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>