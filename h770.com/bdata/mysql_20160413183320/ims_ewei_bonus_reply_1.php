<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_bonus_reply`;");
E_C("CREATE TABLE `ims_ewei_bonus_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `detail` text,
  `rules` text,
  `copyright` varchar(200) DEFAULT '',
  `followurl` varchar(1000) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `joincount` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `points` decimal(10,2) DEFAULT '100.00' COMMENT '多少可以申请提现',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>