<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bobo_zhibo_tuwen`;");
E_C("CREATE TABLE `ims_bobo_zhibo_tuwen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `zhiboid` int(11) NOT NULL,
  `zhibo_picurl` varchar(150) NOT NULL,
  `zhibo_shipin` varchar(150) NOT NULL,
  `zhibo_yinpin` varchar(150) NOT NULL,
  `wenzi` varchar(600) NOT NULL,
  `create_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>