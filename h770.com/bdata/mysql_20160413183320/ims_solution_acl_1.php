<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_solution_acl`;");
E_C("CREATE TABLE `ims_solution_acl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `eid` int(10) unsigned NOT NULL DEFAULT '0',
  `do` varchar(255) NOT NULL,
  `state` varchar(1000) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_module` (`module`),
  KEY `idx_eid` (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>