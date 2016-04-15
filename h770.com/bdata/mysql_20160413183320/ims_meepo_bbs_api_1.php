<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_api`;");
E_C("CREATE TABLE `ims_meepo_bbs_api` (
  `id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(132) NOT NULL DEFAULT '',
  `file` varchar(132) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>