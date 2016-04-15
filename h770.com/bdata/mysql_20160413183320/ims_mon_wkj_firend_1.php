<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_wkj_firend`;");
E_C("CREATE TABLE `ims_mon_wkj_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `k_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>