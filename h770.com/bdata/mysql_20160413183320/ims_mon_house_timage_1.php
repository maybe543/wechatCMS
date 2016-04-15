<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_house_timage`;");
E_C("CREATE TABLE `ims_mon_house_timage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `tid` int(11) DEFAULT '0',
  `pre_img` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>