<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_account_wechats`;");
E_C("CREATE TABLE `ims_account_wechats` (
  `acid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `token` varchar(32) NOT NULL,
  `encodingaeskey` varchar(255) NOT NULL,
  `access_token` varchar(1000) NOT NULL DEFAULT '',
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `account` varchar(30) NOT NULL,
  `original` varchar(50) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `country` varchar(10) NOT NULL,
  `province` varchar(3) NOT NULL,
  `city` varchar(15) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `key` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `styleid` int(10) unsigned NOT NULL DEFAULT '1',
  `jsapi_ticket` varchar(1000) NOT NULL,
  `subscribeurl` varchar(120) NOT NULL,
  `topad` varchar(225) NOT NULL,
  `footad` varchar(225) NOT NULL,
  `auth_refresh_token` varchar(255) NOT NULL,
  PRIMARY KEY (`acid`),
  KEY `idx_key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ims_account_wechats` values('1','1',0x6f6d4a4e705a45685a65486a315a784645434b6b50343842355646626b314850,'','','0',0x7765697a616e,'','','','','','','','','0','','','1','','','','','');");
E_D("replace into `ims_account_wechats` values('2','2',0x753335616d62796f6168676734673133646e7475686f6877356e62707862346c,'','','4',0xe585a8e7bd91e58f91e5b883e6b58be8af95e695b0e68dae,'','','','','','','','','0',0x777835373062633339366135316238666638,'','0','','','','','');");
E_D("replace into `ims_account_wechats` values('3','3',0x65305038347a533644493036365a6447455570516164565269477076366f364a,0x6b57376e513153503470565337587a77774437415a37376e57375134643134505738374d4d46666434776d,'','1',0xe5beaee8b59ee6b58be8af95,'','','','','','','','','0','','','1','','','','','');");

require("../../inc/footer.php");
?>