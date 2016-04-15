<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_valuation_mobile_version`;");
E_C("CREATE TABLE `ims_amouse_valuation_mobile_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `moid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='手机型号'");

require("../../inc/footer.php");
?>