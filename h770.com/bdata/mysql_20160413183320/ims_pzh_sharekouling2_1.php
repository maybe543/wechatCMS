<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_pzh_sharekouling2`;");
E_C("CREATE TABLE `ims_pzh_sharekouling2` (
  `uniacid` int(10) NOT NULL,
  `acid` int(10) NOT NULL,
  `kouling` varchar(250) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>