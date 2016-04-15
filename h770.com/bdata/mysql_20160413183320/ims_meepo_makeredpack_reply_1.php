<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_makeredpack_reply`;");
E_C("CREATE TABLE `ims_meepo_makeredpack_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `weid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `gzurl` varchar(255) DEFAULT NULL,
  `outareaurl` varchar(255) DEFAULT NULL,
  `area` varchar(255) DEFAULT NULL,
  `tjnum` int(11) NOT NULL DEFAULT '10',
  `totalmoney` int(11) NOT NULL DEFAULT '10',
  `firstmin` int(11) NOT NULL DEFAULT '1',
  `firstmax` int(11) NOT NULL DEFAULT '2',
  `secondmin` int(11) NOT NULL DEFAULT '1',
  `secondmax` int(11) NOT NULL DEFAULT '2',
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `_desc` varchar(100) DEFAULT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `mchid` varchar(20) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `signkey` varchar(32) DEFAULT NULL,
  `guanzhu` tinyint(2) NOT NULL DEFAULT '1' COMMENT '关注',
  `guize` text NOT NULL,
  `topbg` varchar(500) NOT NULL,
  `tjtype` int(1) NOT NULL DEFAULT '1',
  `totalnum` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>