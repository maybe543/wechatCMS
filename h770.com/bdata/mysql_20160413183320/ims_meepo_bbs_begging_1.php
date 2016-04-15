<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_begging`;");
E_C("CREATE TABLE `ims_meepo_bbs_begging` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(64) NOT NULL DEFAULT '',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `fopenid` varchar(42) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` float(6,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(32) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` text NOT NULL,
  `ttid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>