<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepomailattachment`;");
E_C("CREATE TABLE `ims_meepomailattachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `attachmentname` varchar(50) NOT NULL COMMENT '附件名称',
  `thumb` varchar(255) NOT NULL COMMENT '附件路径',
  `description` varchar(500) NOT NULL COMMENT '附件描述',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附加排序',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>