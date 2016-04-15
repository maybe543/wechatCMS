<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_egg_reply`;");
E_C("CREATE TABLE `ims_egg_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `rule` text NOT NULL COMMENT '规则',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `default_tips` varchar(100) NOT NULL COMMENT '默认提示信息',
  `hitcredit` int(11) NOT NULL COMMENT '中奖奖励积分',
  `misscredit` int(11) NOT NULL COMMENT '未中奖奖励积分',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>