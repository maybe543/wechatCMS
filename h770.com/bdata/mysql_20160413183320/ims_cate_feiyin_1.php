<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_feiyin`;");
E_C("CREATE TABLE `ims_cate_feiyin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `printtype` varchar(50) DEFAULT NULL,
  `descriptions` text,
  `membercode` varchar(255) DEFAULT NULL,
  `feyinkey` varchar(255) DEFAULT NULL,
  `deviceno` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT '使用中' COMMENT '状态',
  `setting` mediumtext,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 飞印'");

require("../../inc/footer.php");
?>