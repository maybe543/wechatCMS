<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_market_business`;");
E_C("CREATE TABLE `ims_market_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  `classid` int(11) DEFAULT NULL,
  `keyword` varchar(20) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `picurl` varchar(300) DEFAULT NULL,
  `infos` varchar(250) DEFAULT NULL,
  `outlink` varchar(300) DEFAULT NULL,
  `shopname` varchar(50) DEFAULT NULL,
  `description` varchar(600) DEFAULT NULL,
  `logo` varchar(300) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `card_name` varchar(30) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `background` varchar(200) DEFAULT NULL,
  `bgcustom` varchar(200) DEFAULT NULL,
  `card_logo` varchar(200) DEFAULT NULL,
  `font_color` varchar(10) DEFAULT NULL,
  `info` varchar(300) DEFAULT NULL,
  `card_num` varchar(20) DEFAULT NULL,
  `update_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>