<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_brand`;");
E_C("CREATE TABLE `ims_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `bname` varchar(50) NOT NULL,
  `intro` varchar(1000) NOT NULL,
  `intro2` varchar(1000) NOT NULL,
  `video_name` varchar(100) DEFAULT NULL,
  `video_url` varchar(100) DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `pptname` varchar(100) DEFAULT NULL,
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `pic` varchar(100) NOT NULL,
  `visitsCount` int(11) DEFAULT '0',
  `btnName` varchar(20) DEFAULT NULL,
  `btnUrl` varchar(100) DEFAULT NULL,
  `btnName2` varchar(20) DEFAULT NULL,
  `btnUrl2` varchar(100) DEFAULT NULL,
  `btnName3` varchar(20) DEFAULT NULL,
  `btnUrl3` varchar(100) DEFAULT NULL,
  `showMsg` int(1) DEFAULT '0',
  `tel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>