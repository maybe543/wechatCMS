<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_crowdfunding_log`;");
E_C("CREATE TABLE `ims_jy_crowdfunding_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(30) NOT NULL,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `budget` float NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `log` varchar(255) NOT NULL DEFAULT '',
  `completed` varchar(20) NOT NULL DEFAULT '',
  `completetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>