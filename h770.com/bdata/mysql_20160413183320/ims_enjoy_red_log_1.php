<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_red_log`;");
E_C("CREATE TABLE `ims_enjoy_red_log` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(20) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `money` float(20,2) DEFAULT NULL,
  `createtime` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>