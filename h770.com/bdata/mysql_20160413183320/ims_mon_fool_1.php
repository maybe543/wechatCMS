<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_fool`;");
E_C("CREATE TABLE `ims_mon_fool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `follow_url` varchar(200) NOT NULL,
  `new_title` varchar(200) NOT NULL,
  `new_icon` varchar(200) NOT NULL,
  `new_content` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_content` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>