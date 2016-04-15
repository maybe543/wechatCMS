<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fighting_user`;");
E_C("CREATE TABLE `ims_fighting_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `deptid` int(10) NOT NULL COMMENT '部门ID',
  `fid` int(10) unsigned NOT NULL COMMENT '活动ID',
  `nickname` varchar(100) NOT NULL COMMENT '活动ID',
  `mobile` varchar(100) NOT NULL COMMENT '手机号码',
  `openid` varchar(255) NOT NULL COMMENT '手机号码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>