<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2_order`;");
E_C("CREATE TABLE `ims_hotel2_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `btime` int(11) DEFAULT '0',
  `etime` int(11) DEFAULT '0',
  `style` varchar(255) DEFAULT '',
  `nums` int(11) DEFAULT '0',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `info` text,
  `time` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `paytype` int(11) DEFAULT '0',
  `paystatus` int(11) DEFAULT '0',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚',
  `sum_price` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `ordersn` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_memberid` (`memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>