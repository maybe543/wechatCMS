<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_fen_ip_log`;");
E_C("CREATE TABLE `ims_meepo_fen_ip_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uniacid` mediumint(8) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>