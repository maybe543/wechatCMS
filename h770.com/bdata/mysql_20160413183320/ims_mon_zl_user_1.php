<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_zl_user`;");
E_C("CREATE TABLE `ims_mon_zl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(300) NOT NULL,
  `uname` varchar(200) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `point` int(10) DEFAULT NULL,
  `ptime` int(10) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `moid` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>