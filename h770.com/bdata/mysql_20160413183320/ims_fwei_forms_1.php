<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fwei_forms`;");
E_C("CREATE TABLE `ims_fwei_forms` (
  `formid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `info` varchar(100) NOT NULL,
  `num` int(10) NOT NULL DEFAULT '0',
  `max_num` int(10) NOT NULL DEFAULT '0',
  `stime` int(10) unsigned NOT NULL DEFAULT '0',
  `etime` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `show_desc` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `timeline` int(10) unsigned NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `coupon` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` text NOT NULL,
  `redirect` varchar(250) NOT NULL,
  PRIMARY KEY (`formid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>