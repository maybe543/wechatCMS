<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_nsign_record`;");
E_C("CREATE TABLE `ims_nsign_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` text NOT NULL,
  `today_rank` int(11) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `last_sign_time` int(11) NOT NULL,
  `continue_sign_days` int(11) NOT NULL,
  `maxcontinue_sign_days` int(11) NOT NULL,
  `total_sign_num` int(11) NOT NULL,
  `maxtotal_sign_num` int(11) NOT NULL,
  `first_sign_days` int(11) NOT NULL,
  `maxfirst_sign_days` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=366 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>