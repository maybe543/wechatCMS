<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_member`;");
E_C("CREATE TABLE `ims_hc_hunxiao_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `headimg` varchar(255) NOT NULL DEFAULT '',
  `media_id` varchar(255) NOT NULL DEFAULT '' COMMENT '专属名片',
  `shareid` int(10) unsigned NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `pwd` varchar(20) NOT NULL,
  `bankcard` varchar(20) DEFAULT NULL,
  `banktype` varchar(20) DEFAULT NULL,
  `alipay` varchar(100) DEFAULT NULL,
  `wxhao` varchar(100) DEFAULT NULL,
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '已结佣佣金',
  `credit` int(10) unsigned DEFAULT '0' COMMENT '签到积分',
  `content` text,
  `createtime` int(10) NOT NULL,
  `mediatime` int(10) NOT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0为禁用，1为可用',
  `flag` tinyint(1) DEFAULT '0' COMMENT '0为非推广人，1为推广人',
  `ischange` tinyint(1) DEFAULT '0' COMMENT '是否改变海报样式，0否，1是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>