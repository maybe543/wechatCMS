<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_timeline_item`;");
E_C("CREATE TABLE `ims_mon_timeline_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) DEFAULT NULL,
  `ititle` varchar(50) DEFAULT NULL,
  `text` varchar(1000) DEFAULT NULL,
  `i_time` int(10) DEFAULT NULL,
  `i_img` varchar(250) DEFAULT NULL,
  `i_bgcolor` varchar(250) DEFAULT NULL,
  `i_url` varchar(500) DEFAULT NULL,
  `displayorder` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>