<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_print`;");
E_C("CREATE TABLE `ims_cate_print` (
  `id` bigint(18) unsigned NOT NULL AUTO_INCREMENT,
  `txt` text COMMENT '打印内容',
  `key` varchar(255) DEFAULT NULL,
  `setting` text,
  `indate` bigint(18) unsigned DEFAULT '0' COMMENT '加入时间',
  `printdate` bigint(18) unsigned DEFAULT '0' COMMENT '打印时间  等于0时未打印',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 等待打印的内容'");

require("../../inc/footer.php");
?>