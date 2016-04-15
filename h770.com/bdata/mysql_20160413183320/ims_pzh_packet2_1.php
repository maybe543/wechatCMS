<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_pzh_packet2`;");
E_C("CREATE TABLE `ims_pzh_packet2` (
  `uniacid` int(10) NOT NULL,
  `openid` varchar(35) NOT NULL,
  `redPackCount` int(10) NOT NULL,
  `lastTime` int(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>