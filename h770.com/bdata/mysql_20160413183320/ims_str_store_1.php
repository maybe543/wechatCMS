<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_str_store`;");
E_C("CREATE TABLE `ims_str_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `business_hours` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `send_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_time` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `serve_radius` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `delivery_area` varchar(50) NOT NULL,
  `thumbs` varchar(1000) NOT NULL,
  `district` varchar(40) NOT NULL,
  `address` varchar(50) NOT NULL,
  `location_x` varchar(15) NOT NULL,
  `location_y` varchar(15) NOT NULL,
  `email_notice` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notice_acid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>