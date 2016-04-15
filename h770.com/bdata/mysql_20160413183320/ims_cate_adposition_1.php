<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_adposition`;");
E_C("CREATE TABLE `ims_cate_adposition` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `num` tinyint(3) unsigned DEFAULT '0',
  `rand` varchar(20) DEFAULT '随机',
  `width` int(10) DEFAULT NULL,
  `height` int(10) unsigned DEFAULT '0',
  `template` text,
  `lastdate` bigint(18) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 广告位置'");

require("../../inc/footer.php");
?>