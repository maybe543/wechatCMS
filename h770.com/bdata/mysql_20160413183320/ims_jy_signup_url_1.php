<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_url`;");
E_C("CREATE TABLE `ims_jy_signup_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '分类跳转链接',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>