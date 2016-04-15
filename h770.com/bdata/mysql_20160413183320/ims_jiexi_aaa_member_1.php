<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jiexi_aaa_member`;");
E_C("CREATE TABLE `ims_jiexi_aaa_member` (
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(30) NOT NULL,
  `wechat` varchar(50) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent1` int(10) unsigned NOT NULL DEFAULT '0',
  `parent2` int(10) unsigned NOT NULL DEFAULT '0',
  `parent3` int(10) unsigned NOT NULL DEFAULT '0',
  `parent4` int(10) unsigned NOT NULL DEFAULT '0',
  `parent5` int(10) unsigned NOT NULL DEFAULT '0',
  `parent6` int(10) unsigned NOT NULL DEFAULT '0',
  `parent7` int(10) unsigned NOT NULL DEFAULT '0',
  `parent8` int(10) unsigned NOT NULL DEFAULT '0',
  `parent9` int(10) unsigned NOT NULL DEFAULT '0',
  `parent10` int(10) unsigned NOT NULL DEFAULT '0',
  `parent11` int(10) unsigned NOT NULL DEFAULT '0',
  `parent12` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>