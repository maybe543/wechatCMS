<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_tel114_adv`;");
E_C("CREATE TABLE `ims_amouse_tel114_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL,
  `followurl` varchar(1000) DEFAULT '' COMMENT '连接',
  `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片',
  `title` varchar(1000) DEFAULT '' COMMENT '导航名称',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>