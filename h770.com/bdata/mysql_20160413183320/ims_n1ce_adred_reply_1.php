<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_n1ce_adred_reply`;");
E_C("CREATE TABLE `ims_n1ce_adred_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `head` varchar(255) NOT NULL,
  `nullhb` varchar(255) NOT NULL,
  `hb` varchar(255) NOT NULL,
  `adhb` varchar(255) NOT NULL,
  `share_title` varchar(255) NOT NULL,
  `share_des` varchar(255) NOT NULL,
  `share_url` varchar(255) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>