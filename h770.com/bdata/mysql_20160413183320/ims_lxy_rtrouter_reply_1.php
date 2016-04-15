<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lxy_rtrouter_reply`;");
E_C("CREATE TABLE `ims_lxy_rtrouter_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `oktip` varchar(255) NOT NULL COMMENT '规则标题',
  `routerid` int(10) unsigned NOT NULL COMMENT '名片id',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>