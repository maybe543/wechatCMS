<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_ads`;");
E_C("CREATE TABLE `ims_cate_ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `positionid` int(10) unsigned DEFAULT '0',
  `positiontitle` varchar(255) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `img` varchar(255) DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `starttime` bigint(18) unsigned DEFAULT '0',
  `endtime` bigint(18) unsigned DEFAULT '0',
  `txt1` varchar(255) DEFAULT NULL,
  `txt2` varchar(255) DEFAULT NULL,
  `txt3` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `click` int(10) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 广告'");

require("../../inc/footer.php");
?>