<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_setting`;");
E_C("CREATE TABLE `ims_jy_signup_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `aname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `sharedesc` varchar(255) NOT NULL,
  `sharelogo` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `copyrighturl` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>