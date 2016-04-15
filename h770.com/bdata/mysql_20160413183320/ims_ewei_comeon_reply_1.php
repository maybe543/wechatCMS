<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_comeon_reply`;");
E_C("CREATE TABLE `ims_ewei_comeon_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `toppic` varchar(255) DEFAULT '',
  `bgcolor` varchar(255) DEFAULT '',
  `fontcolor` varchar(255) DEFAULT '',
  `btncolor` varchar(255) DEFAULT '',
  `btnfontcolor` varchar(255) DEFAULT '',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `tips` varchar(200) DEFAULT '',
  `info_tips` varchar(200) DEFAULT '' COMMENT '例如 您已经获得 [P] [U]',
  `help_tips` varchar(200) DEFAULT '' COMMENT '例如 给TA助力',
  `join_tips` varchar(200) DEFAULT '' COMMENT '例如 我也来领取加油卡',
  `invite_tips` varchar(200) DEFAULT '' COMMENT '例如 邀请好友助力',
  `rank_tips` varchar(200) DEFAULT '' COMMENT '例如 显示排名',
  `rank_num` int(11) DEFAULT '0' COMMENT '多少名之前的排名',
  `unit` varchar(200) DEFAULT '' COMMENT '单位',
  `ticket_information` varchar(200) DEFAULT '',
  `tel_rename` varchar(200) DEFAULT '',
  `content` text,
  `copyright` varchar(200) DEFAULT '',
  `joincontent` text,
  `overcontent` text,
  `self_times` int(11) DEFAULT '0' COMMENT '活动期间可以被助力几次',
  `self_day_times` int(11) DEFAULT '0' COMMENT '每天可以被助力几次',
  `other_times` int(11) DEFAULT '0' COMMENT '活动期间可给别人助力多少次',
  `other_day_times` int(11) DEFAULT '0' COMMENT '每天可给别人助力多少次',
  `other_one_times` int(11) DEFAULT '0' COMMENT '活动期间可给相同助力多少次',
  `other_one_day_times` int(11) DEFAULT '0' COMMENT '每天可给相同用户助力多少次',
  `type` tinyint(1) DEFAULT '0' COMMENT '规则类型 0 集分 1 集分',
  `show_rank` tinyint(1) DEFAULT '0' COMMENT '显示排名 0 不显示 1 显示',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `show_helps` tinyint(1) DEFAULT '0' COMMENT '是否显示助力数',
  `awardtype` tinyint(1) DEFAULT '0' COMMENT '奖品类型 0 一次性 1 阶梯性',
  `awards` text COMMENT '奖品',
  `rules` text COMMENT '规则',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>