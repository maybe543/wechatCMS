<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_house`;");
E_C("CREATE TABLE `ims_mon_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `news_title` varchar(255) NOT NULL,
  `lpaddress` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `sltel` varchar(25) NOT NULL,
  `zxtel` varchar(25) NOT NULL,
  `news_icon` varchar(255) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  `title` varchar(100) NOT NULL,
  `kptime` int(10) DEFAULT '0',
  `rztime` int(10) DEFAULT '0',
  `kfs` varchar(100) NOT NULL,
  `cover_img` varchar(200) NOT NULL,
  `overview_img` varchar(200) NOT NULL,
  `intro_img` varchar(200) NOT NULL,
  `intro` varchar(1000) DEFAULT NULL,
  `order_title` varchar(50) NOT NULL,
  `order_remark` varchar(100) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` varchar(500) NOT NULL,
  `dt_img` varchar(300) DEFAULT NULL,
  `dt_intro` varchar(2000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>