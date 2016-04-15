<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_sign`;");
E_C("CREATE TABLE `ims_mon_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `follow_credit` int(10) NOT NULL,
  `follow_credit_allow` int(1) DEFAULT '0',
  `leave_credit_clear` int(1) DEFAULT '0',
  `sign_credit` int(11) DEFAULT '0',
  `sync_credit` int(1) DEFAULT '0',
  `rule` varchar(2000) DEFAULT NULL,
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `sin_suc_msg` varchar(200) DEFAULT NULL,
  `sin_suc_fail` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `copyright` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>