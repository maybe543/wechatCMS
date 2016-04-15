<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_sort`;");
E_C("CREATE TABLE `ims_cate_sort` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(10) unsigned DEFAULT '0',
  `arrchildid` text COMMENT '子级栏目（全部）',
  `title` varchar(50) DEFAULT NULL,
  `goodsnum` int(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT '0',
  `setting` mediumtext,
  `view` int(10) unsigned DEFAULT '0',
  `inorder` int(10) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 分类'");

require("../../inc/footer.php");
?>