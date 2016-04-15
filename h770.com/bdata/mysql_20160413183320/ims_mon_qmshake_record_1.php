<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_qmshake_record`;");
E_C("CREATE TABLE `ims_mon_qmshake_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `djtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>