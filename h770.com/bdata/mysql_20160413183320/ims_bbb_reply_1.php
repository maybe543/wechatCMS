<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bbb_reply`;");
E_C("CREATE TABLE `ims_bbb_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uniacid` int(10) unsigned NOT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(500) NOT NULL COMMENT '活动描述',
  `rule` text NOT NULL COMMENT '活动描述',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `headpic` varchar(100) NOT NULL COMMENT '默认提示信息',
  `headurl` varchar(255) NOT NULL DEFAULT '',
  `panzi` varchar(100) NOT NULL DEFAULT '',
  `guzhuurl` varchar(255) NOT NULL DEFAULT '',
  `prace_times` int(10) NOT NULL DEFAULT '100',
  `title` varchar(100) NOT NULL DEFAULT '',
  `start_time` int(10) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '1600000000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>