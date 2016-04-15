<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2_room_price`;");
E_C("CREATE TABLE `ims_hotel2_room_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `roomdate` int(11) DEFAULT '0',
  `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `num` varchar(255) DEFAULT '-1',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_roomdate` (`roomdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>