<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `picture` varchar(225) NOT NULL COMMENT '规则图片',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `tstart_time` int(10) unsigned NOT NULL COMMENT '投票开始时间',
  `tend_time` int(10) unsigned NOT NULL COMMENT '投票结束时间',
  `bstart_time` int(10) unsigned NOT NULL COMMENT '报名开始时间',
  `bend_time` int(10) unsigned NOT NULL COMMENT '报名结束时间',
  `ttipstart` varchar(255) NOT NULL COMMENT '投票开始时间',
  `ttipend` varchar(255) NOT NULL COMMENT '投票结束时间',
  `btipstart` varchar(255) NOT NULL COMMENT '报名开始时间',
  `btipend` varchar(255) NOT NULL COMMENT '报名结束时间',
  `isdaojishi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '倒计时开关',
  `ttipvote` varchar(100) NOT NULL COMMENT '提示',
  `votetime` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `stopping` varchar(225) NOT NULL COMMENT 'fx图片',
  `nostart` varchar(225) NOT NULL COMMENT 'fx图片',
  `end` varchar(225) NOT NULL COMMENT 'fx图片',
  `templates` varchar(50) NOT NULL DEFAULT 'default' COMMENT '默认模板',
  `qiniu` varchar(600) NOT NULL COMMENT '七牛',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>