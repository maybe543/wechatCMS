<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cyl_wxwenzhang_tixian`;");
E_C("CREATE TABLE `ims_cyl_wxwenzhang_tixian` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `uniacid` int(25) NOT NULL,
  `title` varchar(25) NOT NULL,
  `wxh` varchar(25) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `amount` varchar(25) NOT NULL,
  `uid` varchar(25) NOT NULL,
  `createtime` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>