<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_bonus_fans`;");
E_C("CREATE TABLE `ims_ewei_bonus_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headurl` varchar(255) DEFAULT '' COMMENT '头像',
  `area` varchar(255) DEFAULT '' COMMENT '地区',
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `paytype` tinyint(1) DEFAULT '0',
  `account` varchar(255) DEFAULT '',
  `bank` varchar(255) DEFAULT '',
  `points_start` decimal(10,2) DEFAULT '0.00' COMMENT '初始钱数',
  `points_current` decimal(10,2) DEFAULT '0.00' COMMENT '当前钱数',
  `points_help` decimal(10,2) DEFAULT '0.00' COMMENT '合体钱数',
  `points_withdraw` decimal(10,2) DEFAULT '0.00' COMMENT '提取钱数',
  `points_total` decimal(10,2) DEFAULT '0.00' COMMENT '钱数总数',
  `helps` int(11) DEFAULT '0' COMMENT '被帮助数',
  `helpothers` int(11) DEFAULT '0' COMMENT '帮助数',
  `joincount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0' COMMENT '0 未提现 1 已提现',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>