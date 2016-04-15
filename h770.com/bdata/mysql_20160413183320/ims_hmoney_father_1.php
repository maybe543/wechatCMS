<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hmoney_father`;");
E_C("CREATE TABLE `ims_hmoney_father` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `father` varchar(50) NOT NULL COMMENT '父openid',
  `createtime` int(11) unsigned NOT NULL COMMENT '建立时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `father` (`father`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>