<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_money_reply`;");
E_C("CREATE TABLE `ims_ewei_money_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `isfollow` tinyint(1) NOT NULL COMMENT '是否关注',
  `isshow` tinyint(1) DEFAULT '0',
  `info` int(11) DEFAULT '0',
  `c_rate_one` tinyint(1) DEFAULT '0',
  `c_rate_two` tinyint(1) DEFAULT '0',
  `c_rate_three` tinyint(1) DEFAULT '0',
  `c_rate_four` tinyint(1) DEFAULT '0',
  `c_rate_five` tinyint(1) DEFAULT '0',
  `c_rate_six` tinyint(1) DEFAULT '0',
  `c_rate_seven` tinyint(1) DEFAULT '0',
  `c_rate_eight` tinyint(1) DEFAULT '0',
  `c_rate_nine` tinyint(1) DEFAULT '0',
  `game_time` int(11) NOT NULL,
  `title` varchar(200) DEFAULT '',
  `start_picurl` varchar(200) DEFAULT '',
  `reg_first` tinyint(1) NOT NULL COMMENT '游戏前后注册',
  `max_sum` int(11) NOT NULL,
  `min_sum` int(11) NOT NULL,
  `total_remain` int(11) NOT NULL,
  `remain` int(11) NOT NULL,
  `remain_stime` int(11) NOT NULL,
  `remain_etime` int(11) NOT NULL,
  `remain_name` varchar(50) NOT NULL COMMENT '现金劵名称',
  `remain_sm` varchar(15) NOT NULL COMMENT '兑奖密码',
  `valid_time` varchar(100) NOT NULL COMMENT '现金劵有效时间',
  `remain_rule` varchar(100) NOT NULL COMMENT '现金劵规则',
  `rule` text NOT NULL COMMENT '规则',
  `description` text NOT NULL COMMENT '活动简介',
  `alltimes` int(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `daytimes` int(11) NOT NULL COMMENT '每天最大抽奖数',
  `homeurl` varchar(300) NOT NULL COMMENT '微站链接地址',
  `homepicurl` varchar(200) DEFAULT '',
  `followurl` varchar(300) NOT NULL COMMENT '提示关注网址',
  `homename` varchar(50) NOT NULL COMMENT '微站名称',
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `view_times` int(11) NOT NULL,
  `play_times` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>