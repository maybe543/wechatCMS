<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_bonus_fans_record`;");
E_C("CREATE TABLE `ims_ewei_bonus_fans_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `points` decimal(10,2) DEFAULT '0.00' COMMENT '钱数',
  `status` int(11) DEFAULT '0' COMMENT '状态 0 申请 1 已提现',
  `sim` int(11) DEFAULT '0' COMMENT '状态 0 用户 1 模拟',
  `createtime` int(10) DEFAULT '0' COMMENT '申请时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '提现时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>