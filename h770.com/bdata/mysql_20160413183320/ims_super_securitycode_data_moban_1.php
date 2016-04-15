<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_super_securitycode_data_moban`;");
E_C("CREATE TABLE `ims_super_securitycode_data_moban` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `brand` varchar(20) DEFAULT NULL,
  `spec` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `factory` varchar(500) NOT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `creditname` varchar(20) NOT NULL,
  `creditnum` int(10) unsigned NOT NULL,
  `creditstatus` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `stime` int(10) unsigned NOT NULL,
  `createtime` decimal(11,0) NOT NULL,
  `num` int(10) NOT NULL,
  `tourl` varchar(500) DEFAULT NULL,
  `img_banner` varchar(500) DEFAULT NULL,
  `img_logo` varchar(500) DEFAULT NULL COMMENT '图片',
  `video` varchar(500) DEFAULT NULL COMMENT '视频',
  `buyurl` varchar(500) DEFAULT NULL COMMENT '购买链接',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>