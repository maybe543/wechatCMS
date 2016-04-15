<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_feng_wechat`;");
E_C("CREATE TABLE `ims_feng_wechat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `access_token` text,
  `lasttime` char(20) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_image` varchar(500) DEFAULT NULL,
  `share_desc` varchar(300) DEFAULT NULL,
  `win_mess` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>