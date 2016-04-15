<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_orderform`;");
E_C("CREATE TABLE `ims_mon_orderform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `oname` varchar(200) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `odesc` text,
  `address` varchar(200) DEFAULT NULL,
  `p_tel` varchar(50) DEFAULT NULL,
  `p_desc` text,
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(100) NOT NULL,
  `location_c` varchar(100) NOT NULL,
  `location_a` varchar(100) NOT NULL,
  `p_title_pg` varchar(500) DEFAULT NULL,
  `p_titile_url` varchar(500) DEFAULT NULL,
  `copyright` varchar(50) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `emailenable` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>