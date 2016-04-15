<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_loupan`;");
E_C("CREATE TABLE `ims_broke_loupan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `share` varchar(100) NOT NULL DEFAULT '',
  `open` varchar(100) NOT NULL DEFAULT '',
  `ostyle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `music` varchar(100) NOT NULL DEFAULT '',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isloop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dist` varchar(20) DEFAULT '',
  `city` varchar(20) DEFAULT '',
  `province` varchar(20) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `addr` varchar(255) DEFAULT NULL,
  `commission` varchar(20) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `jw_addr` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>