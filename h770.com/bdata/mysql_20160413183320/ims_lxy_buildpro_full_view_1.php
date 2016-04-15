<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_buildpro_full_view`;");
E_C("CREATE TABLE `ims_lxy_buildpro_full_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hsid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `quanjinglink` varchar(500) DEFAULT NULL COMMENT '全景外链',
  `pic_qian` varchar(1023) DEFAULT NULL,
  `pic_hou` varchar(1023) DEFAULT NULL,
  `pic_zuo` varchar(1023) DEFAULT NULL,
  `pic_you` varchar(1023) DEFAULT NULL,
  `pic_shang` varchar(1023) DEFAULT NULL,
  `pic_xia` varchar(1023) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COMMENT='楼盘户型全景'");

require("../../inc/footer.php");
?>