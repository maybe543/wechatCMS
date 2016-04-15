<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_paper_member_data`;");
E_C("CREATE TABLE `ims_ewei_exam_paper_member_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `recordid` int(11) DEFAULT '0' COMMENT '学员考试记录id',
  `questionid` int(11) NOT NULL DEFAULT '0',
  `answer` text,
  `times` int(11) DEFAULT '0' COMMENT '单题用时',
  `createtime` int(11) DEFAULT '0',
  `isright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回答正确',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `pageid` int(11) NOT NULL DEFAULT '0' COMMENT '顺序id',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>