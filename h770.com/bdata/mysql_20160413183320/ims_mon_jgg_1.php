<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_jgg`;");
E_C("CREATE TABLE `ims_mon_jgg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `rule` varchar(1000) DEFAULT NULL,
  `join_intro` varchar(1000) DEFAULT NULL,
  `day_play_count` int(3) DEFAULT '0',
  `follow_btn` varchar(50) DEFAULT '点击参加抽奖活动',
  `follow_welbtn` varchar(50) DEFAULT '欢迎参加微信九宫格',
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `prize_level_0` varchar(100) DEFAULT '没有中奖',
  `prize_name_0` varchar(100) NOT NULL,
  `prize_img_0` varchar(200) NOT NULL,
  `prize_p_0` int(3) NOT NULL,
  `prize_level_1` varchar(100) DEFAULT '一等奖',
  `prize_name_1` varchar(100) NOT NULL,
  `prize_img_1` varchar(200) NOT NULL,
  `prize_p_1` int(3) NOT NULL DEFAULT '0',
  `prize_num_1` int(10) NOT NULL,
  `prize_level_2` varchar(100) DEFAULT '二等奖',
  `prize_name_2` varchar(100) NOT NULL,
  `prize_img_2` varchar(200) NOT NULL,
  `prize_p_2` int(3) NOT NULL,
  `prize_num_2` int(10) NOT NULL DEFAULT '0',
  `prize_level_3` varchar(100) DEFAULT '三等奖',
  `prize_name_3` varchar(100) NOT NULL,
  `prize_img_3` varchar(200) NOT NULL,
  `prize_p_3` int(3) NOT NULL,
  `prize_num_3` int(10) NOT NULL DEFAULT '0',
  `prize_level_4` varchar(100) DEFAULT '四等奖',
  `prize_name_4` varchar(100) NOT NULL,
  `prize_img_4` varchar(200) NOT NULL,
  `prize_p_4` int(3) NOT NULL,
  `prize_num_4` int(10) NOT NULL DEFAULT '0',
  `prize_level_5` varchar(100) DEFAULT '五等奖',
  `prize_name_5` varchar(100) NOT NULL,
  `prize_img_5` varchar(200) NOT NULL,
  `prize_p_5` int(3) NOT NULL,
  `prize_num_5` int(10) NOT NULL DEFAULT '0',
  `prize_level_6` varchar(100) DEFAULT '六等奖',
  `prize_name_6` varchar(100) NOT NULL,
  `prize_img_6` varchar(200) NOT NULL,
  `prize_p_6` int(3) NOT NULL,
  `prize_num_6` int(10) NOT NULL DEFAULT '0',
  `prize_level_7` varchar(100) DEFAULT '七等奖',
  `prize_name_7` varchar(100) NOT NULL,
  `prize_img_7` varchar(200) NOT NULL,
  `prize_p_7` int(3) NOT NULL,
  `prize_num_7` int(10) NOT NULL DEFAULT '0',
  `award_count` int(10) DEFAULT '0',
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `day_award_count` int(10) DEFAULT '0',
  `bg` varchar(1000) DEFAULT NULL,
  `bgcolor` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>