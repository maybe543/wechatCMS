<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_guesshb_draw`;");
E_C("CREATE TABLE `ims_ice_guesshb_draw` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT '0',
  `uniacid` int(10) DEFAULT '1',
  `openid` varchar(64) DEFAULT '',
  `isdraw` char(1) DEFAULT '0',
  `codeid` int(10) DEFAULT '0',
  `time` varchar(32) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>