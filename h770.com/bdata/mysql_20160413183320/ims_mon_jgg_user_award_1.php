<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_jgg_user_award`;");
E_C("CREATE TABLE `ims_mon_jgg_user_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>