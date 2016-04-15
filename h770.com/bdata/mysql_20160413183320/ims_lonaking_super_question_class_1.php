<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lonaking_super_question_class`;");
E_C("CREATE TABLE `ims_lonaking_super_question_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `code` varchar(100) DEFAULT '' COMMENT 'code',
  `count` int(11) DEFAULT '0' COMMENT '数量',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>