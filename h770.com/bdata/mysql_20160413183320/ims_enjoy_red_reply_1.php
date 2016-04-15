<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_red_reply`;");
E_C("CREATE TABLE `ims_enjoy_red_reply` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `rule` varchar(1000) DEFAULT NULL,
  `adept` varchar(1000) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `apic` varchar(200) DEFAULT NULL,
  `fpic` varchar(200) DEFAULT NULL,
  `bgpic` varchar(200) DEFAULT NULL,
  `redpic` varchar(200) DEFAULT NULL,
  `redpic1` varchar(200) DEFAULT NULL,
  `redpic2` varchar(200) DEFAULT NULL,
  `redpic3` varchar(200) DEFAULT NULL,
  `redpic4` varchar(200) DEFAULT NULL,
  `redpic5` varchar(200) DEFAULT NULL,
  `redpic6` varchar(200) DEFAULT NULL,
  `custom` int(2) NOT NULL DEFAULT '0',
  `sucai` varchar(200) DEFAULT NULL,
  `chance` int(20) DEFAULT NULL,
  `share_chance` int(20) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `vnum` int(200) DEFAULT NULL,
  `vmin` int(50) DEFAULT NULL,
  `vmax` int(50) DEFAULT NULL,
  `subscribe` int(2) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `times` int(50) NOT NULL DEFAULT '200',
  `cashgz` int(2) NOT NULL DEFAULT '0',
  `stime` varchar(200) DEFAULT NULL,
  `etime` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>