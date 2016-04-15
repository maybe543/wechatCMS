<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_superman_floor`;");
E_C("CREATE TABLE `ims_superman_floor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `awardprompt` text NOT NULL,
  `currentprompt` text NOT NULL,
  `floorprompt` text NOT NULL,
  `setting` text NOT NULL,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>