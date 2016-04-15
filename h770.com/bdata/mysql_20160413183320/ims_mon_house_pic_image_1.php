<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_house_pic_image`;");
E_C("CREATE TABLE `ims_mon_house_pic_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `pre_img` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>