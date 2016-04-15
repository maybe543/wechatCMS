<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_lottery_share`;");
E_C("CREATE TABLE `ims_hx_lottery_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uinacid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `share_from` varchar(50) NOT NULL,
  `share_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>