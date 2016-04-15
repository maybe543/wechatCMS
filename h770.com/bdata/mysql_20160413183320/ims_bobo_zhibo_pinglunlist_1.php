<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_bobo_zhibo_pinglunlist`;");
E_C("CREATE TABLE `ims_bobo_zhibo_pinglunlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `zhiboid` int(11) NOT NULL,
  `zhiboziid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `content` varchar(600) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>