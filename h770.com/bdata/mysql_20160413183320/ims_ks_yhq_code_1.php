<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `ims_ks_yhq_code`;");
E_C("CREATE TABLE `ims_ks_yhq_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(500) NOT NULL,
  `pid` int(11) NOT NULL,
  `use` int(11) NOT NULL DEFAULT '0',
  `void` int(11) NOT NULL DEFAULT '0',
  `send` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>