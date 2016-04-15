<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_tobe`;");
E_C("CREATE TABLE `ims_cate_tobe` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) DEFAULT NULL,
  `userid` int(10) unsigned DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `num` int(11) unsigned DEFAULT '0' COMMENT '排号',
  `indate` bigint(18) unsigned DEFAULT '0',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '状态：\r\n0正常；\r\n1已使用；\r\n99取消。\r\n',
  `setting` text,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 排号'");

require("../../inc/footer.php");
?>