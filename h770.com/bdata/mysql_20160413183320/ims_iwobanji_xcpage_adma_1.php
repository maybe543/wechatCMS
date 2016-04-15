<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_iwobanji_xcpage_adma`;");
E_C("CREATE TABLE `ims_iwobanji_xcpage_adma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `copyright` varchar(50) NOT NULL,
  `info` varchar(120) NOT NULL,
  `title` varchar(60) NOT NULL,
  `class` varchar(60) NOT NULL,
  `classkouling` varchar(60) NOT NULL,
  `classslogan` varchar(60) NOT NULL,
  `background_img` varchar(60) NOT NULL,
  `group_photo` varchar(60) NOT NULL,
  `wxh` varchar(60) NOT NULL,
  `wxm` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`weid`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>