<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_money_fans`;");
E_C("CREATE TABLE `ims_ewei_money_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `isplay` tinyint(1) DEFAULT '0',
  `info` tinyint(1) DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号码',
  `sum` float DEFAULT NULL,
  `remain` int(11) NOT NULL,
  `max_score` float NOT NULL,
  `alltimes` int(11) NOT NULL COMMENT '总剩余次数',
  `daytimes` int(11) NOT NULL COMMENT '当天剩余次数',
  `lasttime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>