<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_topic_replie`;");
E_C("CREATE TABLE `ims_meepo_bbs_topic_replie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `create_at` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `thumb` text NOT NULL,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `beggingid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=427 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>