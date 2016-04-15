<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_hd`;");
E_C("CREATE TABLE `ims_jy_signup_hd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `mendianid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `hdname` varchar(200) NOT NULL DEFAULT '',
  `hdcateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类别id',
  `thumb` text,
  `renshu` varchar(200) NOT NULL DEFAULT '',
  `time` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `lng` varchar(10) DEFAULT NULL,
  `lat` varchar(10) DEFAULT NULL,
  `description` text,
  `num` int(10) NOT NULL,
  `pv` int(10) NOT NULL COMMENT '浏览量',
  `sc` int(10) NOT NULL COMMENT '人气',
  `pl` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>