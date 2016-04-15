<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_album`;");
E_C("CREATE TABLE `ims_lxy_buildpro_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '相册名称',
  `subtitle` varchar(255) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id ims_lxy_buildpro_set table id',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  `pic` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 COMMENT='楼盘相册'");

require("../../inc/footer.php");
?>