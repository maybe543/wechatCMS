<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_dianyuan`;");
E_C("CREATE TABLE `ims_jy_signup_dianyuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `uid` int(10) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `username` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(20) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `QQ` varchar(200) DEFAULT NULL,
  `wechat` varchar(200) DEFAULT NULL,
  `mendianid` int(10) DEFAULT '0',
  `type` int(10) DEFAULT '0',
  `password` varchar(200) DEFAULT NULL,
  `description` text,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>