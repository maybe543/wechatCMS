<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lw_comments`;");
E_C("CREATE TABLE `ims_lw_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `toUser` varchar(50) NOT NULL,
  `content` varchar(300) NOT NULL DEFAULT '',
  `createtime` varchar(100) NOT NULL,
  `nowColor` varchar(50) NOT NULL,
  `limit` tinyint(2) NOT NULL DEFAULT '0',
  `isok` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>