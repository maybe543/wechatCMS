<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2`;");
E_C("CREATE TABLE `ims_hotel2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `lng` decimal(10,2) DEFAULT '0.00',
  `lat` decimal(10,2) DEFAULT '0.00',
  `ordermax` int(11) DEFAULT '0',
  `numsmax` int(11) DEFAULT '0',
  `daymax` int(11) DEFAULT '0',
  `address` varchar(255) DEFAULT '',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `roomcount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `phone` varchar(255) DEFAULT '',
  `mail` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `thumborder` varchar(255) DEFAULT '',
  `description` text,
  `content` text,
  `traffic` text,
  `thumbs` text,
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `device` text,
  `brandid` int(11) DEFAULT '0',
  `businessid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>