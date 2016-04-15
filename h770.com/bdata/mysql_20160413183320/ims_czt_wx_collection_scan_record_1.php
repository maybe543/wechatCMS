<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_czt_wx_collection_scan_record`;");
E_C("CREATE TABLE `ims_czt_wx_collection_scan_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `class_id` tinyint(4) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `founder_openid` varchar(40) NOT NULL DEFAULT '',
  `tid` varchar(64) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `scan_type` tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `code_url` varchar(512) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>