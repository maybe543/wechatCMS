<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_brand_note`;");
E_C("CREATE TABLE `ims_brand_note` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>