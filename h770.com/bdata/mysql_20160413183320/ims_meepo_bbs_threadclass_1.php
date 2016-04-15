<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_threadclass`;");
E_C("CREATE TABLE `ims_meepo_bbs_threadclass` (
  `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `moderators` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `group` varchar(132) DEFAULT NULL,
  `look_group` varchar(232) DEFAULT NULL,
  `post_group` varchar(232) DEFAULT NULL,
  `isgood` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=288 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>