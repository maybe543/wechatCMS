<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_hd_cy`;");
E_C("CREATE TABLE `ims_jy_signup_hd_cy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `nicheng` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `mail` varchar(200) NOT NULL,
  `qq` varchar(200) NOT NULL,
  `wechat` varchar(200) NOT NULL,
  `weibo` varchar(200) NOT NULL,
  `company` varchar(200) NOT NULL,
  `education` varchar(200) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(10) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>