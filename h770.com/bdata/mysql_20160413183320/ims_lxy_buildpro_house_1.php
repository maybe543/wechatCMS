<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_house`;");
E_C("CREATE TABLE `ims_lxy_buildpro_house` (
  `hsid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '户型名称',
  `sid` int(11) DEFAULT NULL COMMENT '子楼盘 ims_lxy_buildpro_set id',
  `louceng` smallint(1) DEFAULT NULL COMMENT '楼层',
  `mianji` varchar(255) DEFAULT NULL COMMENT '建筑面积',
  `fang` tinyint(4) DEFAULT NULL,
  `ting` tinyint(4) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `pic` text,
  `picjson` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hsid`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COMMENT='楼盘户型'");

require("../../inc/footer.php");
?>