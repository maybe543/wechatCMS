<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fwei_forms_attrs`;");
E_C("CREATE TABLE `ims_fwei_forms_attrs` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `formid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `type` varchar(10) NOT NULL,
  `extra` varchar(500) NOT NULL,
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rule` varchar(100) NOT NULL,
  `defvalue` varchar(100) NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>