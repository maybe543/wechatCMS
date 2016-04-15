<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_expert_comment`;");
E_C("CREATE TABLE `ims_lxy_buildpro_expert_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `expert_name` varchar(20) DEFAULT NULL,
  `zhiwei` varchar(255) DEFAULT NULL COMMENT '专家职位',
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `content` text COMMENT '点评内容',
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='楼盘-专家点评'");

require("../../inc/footer.php");
?>