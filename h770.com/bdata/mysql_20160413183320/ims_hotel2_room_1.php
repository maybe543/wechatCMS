<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2_room`;");
E_C("CREATE TABLE `ims_hotel2_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotelid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `thumbs` text,
  `device` text,
  `area` varchar(255) DEFAULT '',
  `floor` varchar(255) DEFAULT '',
  `smoke` varchar(255) DEFAULT '',
  `bed` varchar(255) DEFAULT '',
  `persons` int(11) DEFAULT '0',
  `bedadd` varchar(30) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `isshow` int(11) DEFAULT '0',
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `area_show` int(11) DEFAULT '0',
  `floor_show` int(11) DEFAULT '0',
  `smoke_show` int(11) DEFAULT '0',
  `bed_show` int(11) DEFAULT '0',
  `persons_show` int(11) DEFAULT '0',
  `bedadd_show` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0' COMMENT '订房积分',
  `breakfast` tinyint(3) DEFAULT '0' COMMENT '0无早 1单早 2双早',
  `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id，排序时使用',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>