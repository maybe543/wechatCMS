<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_brand_message`;");
E_C("CREATE TABLE `ims_brand_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` int(11) unsigned DEFAULT NULL,
  `bid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>