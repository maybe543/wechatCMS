<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_member`;");
E_C("CREATE TABLE `ims_broke_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `tjmid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `bankcard` varchar(20) DEFAULT NULL,
  `banktype` varchar(20) DEFAULT NULL,
  `identity` int(10) unsigned NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `commission` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>