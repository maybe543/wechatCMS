<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_dream_reply`;");
E_C("CREATE TABLE `ims_dream_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `picurl` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `share_title` varchar(50) DEFAULT '',
  `share_content` varchar(255) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `dreamnum` int(11) DEFAULT '0',
  `logo` varchar(200) DEFAULT NULL,
  `gzurl` varchar(255) DEFAULT NULL,
  `slogans` varchar(28) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>