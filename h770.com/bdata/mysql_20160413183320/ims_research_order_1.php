<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_research_order`;");
E_C("CREATE TABLE `ims_research_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `rerid` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `mobile` bigint(50) NOT NULL,
  `trans_id` varchar(255) DEFAULT NULL,
  `order_sn` varchar(20) NOT NULL,
  `price` decimal(11,2) DEFAULT '0.00',
  `status` tinyint(4) NOT NULL COMMENT '0已完成，1等待支付',
  `pay_type` tinyint(11) unsigned NOT NULL COMMENT '支付类型',
  `pay_pattern` int(1) DEFAULT '1' COMMENT '支付方式 1-在线付款，2-货到付款',
  `other` varchar(100) NOT NULL DEFAULT '',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_sn_uniacid` (`order_sn`,`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>