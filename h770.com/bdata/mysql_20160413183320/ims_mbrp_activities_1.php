<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mbrp_activities`;");
E_C("CREATE TABLE `ims_mbrp_activities` (
  `actid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动编号',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '活动名称',
  `start` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL COMMENT '结束时间',
  `rules` text NOT NULL COMMENT '活动规则介绍',
  `guide` varchar(255) NOT NULL COMMENT '活动指南(图文素材地址)',
  `banner` varchar(500) NOT NULL COMMENT '背景图片',
  `type` varchar(10) NOT NULL COMMENT '活动类型(direct, shared)',
  `limit` varchar(1000) NOT NULL DEFAULT '',
  `share` varchar(1000) NOT NULL DEFAULT '',
  `tag` text NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`actid`),
  KEY `type` (`type`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>