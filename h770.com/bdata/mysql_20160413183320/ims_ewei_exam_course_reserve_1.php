<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_course_reserve`;");
E_C("CREATE TABLE `ims_ewei_exam_course_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `ordersn` varchar(255) DEFAULT '',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");

require("../../inc/footer.php");
?>