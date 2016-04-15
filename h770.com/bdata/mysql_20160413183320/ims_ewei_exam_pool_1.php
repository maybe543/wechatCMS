<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_exam_pool`;");
E_C("CREATE TABLE `ims_ewei_exam_pool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>