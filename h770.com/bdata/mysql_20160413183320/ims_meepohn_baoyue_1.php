<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepohn_baoyue`;");
E_C("CREATE TABLE `ims_meepohn_baoyue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `tid` varchar(20) NOT NULL DEFAULT '0' COMMENT '֩եҠۅ',
  `openid` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fee` int(10) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `paytype` varchar(20) NOT NULL,
  `transid` varchar(30) NOT NULL DEFAULT '0',
  `time` int(12) NOT NULL,
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>