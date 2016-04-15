<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fighting_level`;");
E_C("CREATE TABLE `ims_fighting_level` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0',
  `levelname` varchar(100) NOT NULL DEFAULT '' COMMENT '等级名称',
  `min` int(10) NOT NULL DEFAULT '0' COMMENT '开始积分',
  `max` int(10) NOT NULL DEFAULT '0' COMMENT '结束积分',
  `dateline` int(10) NOT NULL DEFAULT '0' COMMENT '发布日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>