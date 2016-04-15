<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_gandl_puzzle_user`;");
E_C("CREATE TABLE `ims_gandl_puzzle_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `puzzle_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `clue_idx` int(11) NOT NULL COMMENT '我的随机线索序号',
  `clue_con` varchar(255) NOT NULL COMMENT '我的随机线索内容',
  `clues` text COMMENT '我收集到的线索序号，用,分隔',
  `froms` text COMMENT '我收集到的线索来源',
  `answer` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `answer_time` int(11) DEFAULT NULL,
  `join_channel` tinyint(2) DEFAULT NULL,
  `join_channel_id` int(11) DEFAULT NULL,
  `join_time` int(11) NOT NULL,
  `rank` int(11) NOT NULL COMMENT '排名缓存（主要用于快速对用户显示其排名）：0无排名，>=1排名',
  `award_code` varchar(255) DEFAULT NULL COMMENT '中奖码',
  `award_time` int(11) DEFAULT NULL COMMENT '领奖时间',
  `award_remark` text COMMENT '领奖备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>