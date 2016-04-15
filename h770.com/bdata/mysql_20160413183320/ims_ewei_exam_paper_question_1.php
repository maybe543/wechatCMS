<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_paper_question`;");
E_C("CREATE TABLE `ims_ewei_exam_paper_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionid` int(11) DEFAULT '0' COMMENT '题ID',
  `displayorder` int(11) DEFAULT '0' COMMENT '题目排序',
  `paperid` bigint(20) NOT NULL DEFAULT '0' COMMENT '试卷ID',
  PRIMARY KEY (`id`),
  KEY `idx_questionid` (`questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>