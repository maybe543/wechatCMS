<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_question`;");
E_C("CREATE TABLE `ims_ewei_exam_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `poolid` int(11) DEFAULT '0' COMMENT '题库ID',
  `paperid1` int(11) DEFAULT '0' COMMENT '题库ID',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `level` int(11) DEFAULT '0' COMMENT '难度',
  `question` text COMMENT '问题',
  `thumb` varchar(255) DEFAULT '' COMMENT '问题图片',
  `answer` text COMMENT '答案',
  `isimg` tinyint(1) DEFAULT '0' COMMENT '答案是否包含图片',
  `explain` text COMMENT '讲解',
  `fansnum` int(11) DEFAULT '0' COMMENT '多少人做过',
  `correctnum` int(11) DEFAULT '0' COMMENT '多少人正确',
  `items` text,
  `img_items` text,
  PRIMARY KEY (`id`),
  KEY `idx_poolid` (`poolid`),
  KEY `idx_type` (`type`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>