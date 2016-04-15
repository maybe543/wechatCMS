<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_market_class`;");
E_C("CREATE TABLE `ims_market_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `classname` varchar(20) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `infos` varchar(250) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  `shop_nums` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>