<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hnmessage`;");
E_C("CREATE TABLE `ims_hnmessage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `content` varchar(300) NOT NULL,
  `sender` varchar(255) NOT NULL,
  `sendernickname` varchar(200) NOT NULL,
  `senderavatar` varchar(255) NOT NULL,
  `geter` varchar(255) NOT NULL,
  `stime` int(12) NOT NULL,
  `mloop` tinyint(1) NOT NULL DEFAULT '0',
  `msgtype` varchar(20) NOT NULL DEFAULT 'text' COMMENT 'leixing',
  `thumburl` varchar(100) NOT NULL DEFAULT '0' COMMENT 'thumb',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>