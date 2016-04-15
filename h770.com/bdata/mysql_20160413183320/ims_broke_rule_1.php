<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_rule`;");
E_C("CREATE TABLE `ims_broke_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT '',
  `rule` text,
  `terms` text,
  `createtime` int(10) NOT NULL,
  `gzurl` varchar(255) NOT NULL,
  `teamfy` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>