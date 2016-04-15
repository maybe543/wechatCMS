<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_crowdfunding_member_a`;");
E_C("CREATE TABLE `ims_jy_crowdfunding_member_a` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `destination` varchar(200) NOT NULL,
  `budget` float NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>