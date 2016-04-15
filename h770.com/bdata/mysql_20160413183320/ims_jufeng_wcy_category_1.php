<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jufeng_wcy_category`;");
E_C("CREATE TABLE `ims_jufeng_wcy_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID,0为店铺',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `sendprice` int(10) unsigned NOT NULL DEFAULT '0',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `shouji` bigint(50) NOT NULL COMMENT '店家手机',
  `email` varchar(50) NOT NULL DEFAULT '',
  `typeid` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `time1` varchar(10) NOT NULL DEFAULT '0',
  `time2` varchar(10) NOT NULL DEFAULT '0',
  `time3` varchar(10) NOT NULL DEFAULT '0',
  `time4` varchar(10) NOT NULL DEFAULT '0',
  `address` varchar(100) NOT NULL,
  `loc_x` varchar(20) NOT NULL,
  `loc_y` varchar(20) NOT NULL,
  `mbgroup` int(10) unsigned NOT NULL,
  `count1` varchar(20) NOT NULL,
  `count2` varchar(20) NOT NULL,
  `count3` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>