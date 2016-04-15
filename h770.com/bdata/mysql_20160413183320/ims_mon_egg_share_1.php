<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_egg_share`;");
E_C("CREATE TABLE `ims_mon_egg_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `egid` int(10) NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>