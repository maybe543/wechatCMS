<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_circle_fans`;");
E_C("CREATE TABLE `ims_enjoy_circle_fans` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `proxy` varchar(40) NOT NULL DEFAULT '',
  `unionid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(2) DEFAULT '',
  `state` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `puid` int(20) DEFAULT NULL,
  `black` int(2) NOT NULL DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  `subscribe` int(2) DEFAULT NULL,
  `subscribe_time` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `proxy` (`proxy`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>