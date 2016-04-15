<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_yjgz_item`;");
E_C("CREATE TABLE `ims_mon_yjgz_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `i_desc` varchar(500) NOT NULL,
  `i_url` varchar(300) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>