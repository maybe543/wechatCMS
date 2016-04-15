<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_paper_type`;");
E_C("CREATE TABLE `ims_ewei_exam_paper_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(255) DEFAULT '' COMMENT '试卷标题',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '分值',
  `types` text COMMENT '试题类型设置 包含试题类型 试题分数',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试时间',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>