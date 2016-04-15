<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_yzmhb_sendlist`;");
E_C("CREATE TABLE `ims_ice_yzmhb_sendlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `codeid` int(10) DEFAULT '1',
  `openid` varchar(64) DEFAULT '',
  `packetid` varchar(32) DEFAULT '',
  `yzmhbid` varchar(32) DEFAULT '',
  `money` varchar(64) DEFAULT '',
  `type` char(20) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `time` varchar(20) DEFAULT '1',
  `mark` varchar(128) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>