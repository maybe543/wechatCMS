<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_fen_click`;");
E_C("CREATE TABLE `ims_meepo_fen_click` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` mediumint(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `openid` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `success` tinyint(2) NOT NULL,
  `father` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>