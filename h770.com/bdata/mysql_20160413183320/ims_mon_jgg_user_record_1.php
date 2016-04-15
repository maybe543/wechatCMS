<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_jgg_user_record`;");
E_C("CREATE TABLE `ims_mon_jgg_user_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>