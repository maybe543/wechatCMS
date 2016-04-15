<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_qw_cjhdpt_user_cache`;");
E_C("CREATE TABLE `ims_qw_cjhdpt_user_cache` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `openid` varchar(50) NOT NULL COMMENT '统一身份',
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `gender` smallint(1) NOT NULL DEFAULT '0',
  `mobile` varchar(20) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `createtime` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>