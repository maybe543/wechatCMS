<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hl_periarthritis`;");
E_C("CREATE TABLE `ims_hl_periarthritis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `shaketimes` int(10) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL COMMENT '活动图片',
  `gzurl` varchar(255) NOT NULL COMMENT '关注URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>