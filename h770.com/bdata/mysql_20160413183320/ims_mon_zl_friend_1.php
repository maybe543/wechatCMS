<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_zl_friend`;");
E_C("CREATE TABLE `ims_mon_zl_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `point` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>