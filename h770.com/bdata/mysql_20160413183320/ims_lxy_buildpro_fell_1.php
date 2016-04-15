<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_fell`;");
E_C("CREATE TABLE `ims_lxy_buildpro_fell` (
  `yid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `yinxiang_number` int(11) unsigned DEFAULT '0' COMMENT '印象数',
  `isshow` tinyint(1) DEFAULT '1',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`yid`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COMMENT='房友印象'");

require("../../inc/footer.php");
?>