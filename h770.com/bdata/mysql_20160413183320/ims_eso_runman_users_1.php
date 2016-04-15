<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_eso_runman_users`;");
E_C("CREATE TABLE `ims_eso_runman_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `ruid` int(11) DEFAULT NULL COMMENT '来源会员ID',
  `rnum` int(10) unsigned DEFAULT '0' COMMENT '被访问次数',
  `title` varchar(255) DEFAULT '',
  `sex` varchar(10) DEFAULT '',
  `tag` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `img` varchar(255) DEFAULT '',
  `openid` varchar(255) DEFAULT '',
  `indate` bigint(18) unsigned DEFAULT '0' COMMENT '入住时间',
  `ladate` varchar(20) DEFAULT '',
  `defaultval` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '初始暖值',
  `ruidval` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '别人加的暖值',
  `val` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '当前暖值（暖值总）',
  `one` tinyint(3) unsigned DEFAULT '0' COMMENT '第一次进入',
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 会员'");

require("../../inc/footer.php");
?>