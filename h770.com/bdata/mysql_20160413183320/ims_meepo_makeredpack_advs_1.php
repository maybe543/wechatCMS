<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_makeredpack_advs`;");
E_C("CREATE TABLE `ims_meepo_makeredpack_advs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT 'meepo',
  `advurl` varchar(200) NOT NULL DEFAULT 'meepo',
  `logo` varchar(200) NOT NULL DEFAULT 'meepo',
  `createtime` int(12) NOT NULL DEFAULT '1' COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>