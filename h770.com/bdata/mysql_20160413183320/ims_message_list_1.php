<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_message_list`;");
E_C("CREATE TABLE `ims_message_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `weid` int(11) NOT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>