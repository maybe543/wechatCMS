<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_survey_rows`;");
E_C("CREATE TABLE `ims_survey_rows` (
  `srid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `suggest` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`srid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>