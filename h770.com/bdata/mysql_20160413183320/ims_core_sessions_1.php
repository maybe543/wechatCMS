<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_core_sessions`;");
E_C("CREATE TABLE `ims_core_sessions` (
  `sid` char(32) NOT NULL DEFAULT '',
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `data` varchar(5000) NOT NULL,
  `expiretime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ims_core_sessions` values(0x617672716436613165696a366b71666b333865756f6964317132,'3',0x3a3a31,0x616369647c733a313a2233223b756e69616369647c693a333b746f6b656e7c613a313a7b733a343a2261325847223b693a313436303534323831363b7d,'1460546416');");

require("../../inc/footer.php");
?>