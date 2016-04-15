<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_card`;");
E_C("CREATE TABLE `ims_cate_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned DEFAULT '0',
  `cardid` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `indate` bigint(18) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned DEFAULT '0',
  `setting` text,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 卡券'");

require("../../inc/footer.php");
?>