<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2_member`;");
E_C("CREATE TABLE `ims_hotel2_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `userid` varchar(50) DEFAULT '',
  `from_user` varchar(50) DEFAULT '',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `score` int(11) DEFAULT '0' COMMENT '积分',
  `createtime` int(11) DEFAULT '0',
  `userbind` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `username` varchar(30) DEFAULT '' COMMENT '用户名',
  `password` varchar(200) DEFAULT '' COMMENT '密码',
  `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐',
  `islogin` tinyint(3) NOT NULL DEFAULT '0',
  `isauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动添加，0否，1是',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>