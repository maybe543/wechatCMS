<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_signup_member`;");
E_C("CREATE TABLE `ims_jy_signup_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `wechatid` int(10) NOT NULL,
  `from_user` varchar(30) NOT NULL,
  `nicheng` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `status` int(2) NOT NULL,
  `type` int(2) NOT NULL COMMENT '1为微信,0为账户',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>