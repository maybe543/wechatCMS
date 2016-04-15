<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_j_activity_record`;");
E_C("CREATE TABLE `ims_j_activity_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `aid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `ip` varchar(20) NOT NULL COMMENT 'IP地址',
  `from_user` varchar(20) NOT NULL COMMENT '用户openid',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>