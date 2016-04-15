<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_house_item`;");
E_C("CREATE TABLE `ims_mon_house_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `iname` varchar(255) NOT NULL,
  `icontent` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>