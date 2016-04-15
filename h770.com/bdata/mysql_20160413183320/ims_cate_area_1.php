<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_area`;");
E_C("CREATE TABLE `ims_cate_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned DEFAULT '0',
  `arrchildid` varchar(255) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `goodsnum` int(10) DEFAULT NULL,
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT '0',
  `setting` mediumtext,
  `view` int(10) unsigned DEFAULT '0',
  `inorder` int(10) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 地区'");

require("../../inc/footer.php");
?>