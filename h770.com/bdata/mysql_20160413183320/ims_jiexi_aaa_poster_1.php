<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jiexi_aaa_poster`;");
E_C("CREATE TABLE `ims_jiexi_aaa_poster` (
  `poster_id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `active` int(10) unsigned NOT NULL DEFAULT '0',
  `follow` varchar(500) NOT NULL,
  `notmember` varchar(500) NOT NULL,
  `bg` varchar(200) NOT NULL,
  `bgparam` text NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`poster_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>