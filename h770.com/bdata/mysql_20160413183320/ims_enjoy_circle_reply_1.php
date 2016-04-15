<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_enjoy_circle_reply`;");
E_C("CREATE TABLE `ims_enjoy_circle_reply` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `subscribe` int(2) DEFAULT '0',
  `sucai` varchar(200) DEFAULT NULL,
  `exurl` varchar(500) DEFAULT NULL,
  `expic` varchar(200) DEFAULT NULL,
  `extitle` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `ewm` varchar(200) DEFAULT NULL,
  `bgpic` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>