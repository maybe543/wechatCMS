<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ju_read_reply`;");
E_C("CREATE TABLE `ims_ju_read_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `topimg` varchar(255) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  `pagestyle` longtext NOT NULL,
  `address` text NOT NULL,
  `tips` varchar(500) NOT NULL,
  `linkurl` varchar(200) NOT NULL,
  `adimg` varchar(255) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `copyright` varchar(20) NOT NULL,
  `prizes` longtext NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>