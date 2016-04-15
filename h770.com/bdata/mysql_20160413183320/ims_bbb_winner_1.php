<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bbb_winner`;");
E_C("CREATE TABLE `ims_bbb_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `point` int(10) unsigned NOT NULL COMMENT '点数',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未领奖，1不需要领奖，2已领奖',
  `createtime` int(10) unsigned NOT NULL COMMENT '获奖日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>