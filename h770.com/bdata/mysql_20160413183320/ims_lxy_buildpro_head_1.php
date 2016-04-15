<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_head`;");
E_C("CREATE TABLE `ims_lxy_buildpro_head` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `xcname` varchar(255) DEFAULT NULL,
  `headpic` varchar(255) DEFAULT NULL,
  `apartpic` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `jw_addr` varchar(255) DEFAULT NULL,
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `jianjie` text,
  `xiangmu` text,
  `jiaotong` text,
  `addr` varchar(255) DEFAULT NULL,
  `yyurl` varchar(500) DEFAULT NULL,
  `xwurl` varchar(500) DEFAULT NULL,
  `hyurl` varchar(500) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `lxname` varchar(50) DEFAULT NULL,
  `hyname` varchar(50) DEFAULT NULL,
  `yyname` varchar(50) DEFAULT NULL,
  `xwname` varchar(50) DEFAULT NULL,
  `yxname` varchar(50) DEFAULT NULL,
  `hxname` varchar(50) DEFAULT NULL,
  `jjname` varchar(50) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COMMENT='楼盘简介'");

require("../../inc/footer.php");
?>