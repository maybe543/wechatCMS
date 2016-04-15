<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickdynamics_option`;");
E_C("CREATE TABLE `ims_quickdynamics_option` (
  `running` int(10) NOT NULL,
  `lasttime` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
E_D("replace into `ims_quickdynamics_option` values('0','0');");

require("../../inc/footer.php");
?>