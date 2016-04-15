<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_paper_member_record`;");
E_C("CREATE TABLE `ims_ewei_exam_paper_member_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `countdown` int(11) DEFAULT '0' COMMENT '倒计时',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '得分',
  `did` int(11) DEFAULT '0' COMMENT '是否完成考试',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>