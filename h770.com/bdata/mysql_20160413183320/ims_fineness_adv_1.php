<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fineness_adv`;");
E_C("CREATE TABLE `ims_fineness_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `pcateid` int(11) DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父ID',
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片'");

require("../../inc/footer.php");
?>