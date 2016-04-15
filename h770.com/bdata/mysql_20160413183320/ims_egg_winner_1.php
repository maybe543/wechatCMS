<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_egg_winner`;");
E_C("CREATE TABLE `ims_egg_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `aid` int(10) unsigned NOT NULL COMMENT '奖品ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `isaward` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `award` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '中奖信息描述',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL COMMENT '获奖日期',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>