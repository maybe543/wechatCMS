<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_guesshb_details`;");
E_C("CREATE TABLE `ims_ice_guesshb_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `gid` int(10) DEFAULT '0',
  `codeid` int(10) DEFAULT '0',
  `money` int(10) DEFAULT '0',
  `status` char(1) DEFAULT '0',
  `time` varchar(64) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>